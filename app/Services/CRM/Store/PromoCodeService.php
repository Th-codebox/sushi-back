<?php


namespace App\Services\CRM\Store;


use App\Enums\OrderStatus;
use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Libraries\Helpers\SettingHelper;
use App\Models\System\Client;
use App\Repositories\Store\PromoCodeRepository;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\ClientService;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;

/**
 * Class PromoCodeService
 * @package App\Services\CRM\System
 * @method PromoCodeRepository getRepository()
 */
class PromoCodeService extends CRMBaseService
{


    public function __construct(?PromoCodeRepository $repository = null)
    {
        parent::__construct($repository);
    }

    public function dataCorrection(array $data)
    {
        $data = parent::dataCorrection($data);

        if (array_key_exists('saleSubtraction', $data) && is_numeric($data['saleSubtraction'])) {
            $data['saleSubtraction'] *= 100;
        }
        return $data;
    }

    /**
     * @param int $clientId
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     */
    public function clientValidate(int $clientId): void
    {


        $promoCodeModel = $this->getRepository()->getModel();


        if ($promoCodeModel->time_end && $promoCodeModel->time_end->unix() < Carbon::now()->unix()) {
            throw new CRMServiceException('Действие промокода закончено!');
        }

        if ($promoCodeModel->time_available_from && $promoCodeModel->time_available_to) {
            if (($promoCodeModel->time_available_from->hour > Carbon::now()->hour) || $promoCodeModel->time_available_to->hour <= Carbon::now()->hour) {
                throw new CRMServiceException('Данный  промокод доступен с ' . $promoCodeModel->time_available_from->format('H:i') . ' до ' . $promoCodeModel->time_available_to->format('H:i'));
            }
        }

        if ($promoCodeModel->day_of_week_begin && $promoCodeModel->day_of_week_end) {
            if ($promoCodeModel->day_of_week_begin > Carbon::now()->dayOfWeekIso || $promoCodeModel->day_of_week_end < Carbon::now()->dayOfWeekIso) {
                throw new CRMServiceException('Данный промокод сегодня не доступен');
            }
        }

        try {
            $activePromoCode = ClientPromoCodeService::findOne([
                'clientId'    => $clientId,
                'promoCodeId' => $promoCodeModel->id,
                'activated'   => true,
                'orderId'     => null,
            ]);

            if ($activePromoCode->edit(['activated' => false])) {
                $activePromoCode = null;
            }
        } catch (\Throwable $e) {

        }

        if ((string)$promoCodeModel->action !== PromoCodeAction::FriendPercent && (string)$promoCodeModel->action !== PromoCodeAction::BirthDay && !$promoCodeModel->repeat) {

            try {
                $activePromoCode = ClientPromoCodeService::findOne([
                    'clientId'    => $clientId,
                    'promoCodeId' => $promoCodeModel->id,
                    '!orderId'    => null,
                ]);
            } catch (\Throwable $e) {
                $activePromoCode = null;
            }

            if ($activePromoCode !== null) {
                throw new CRMServiceException('Данный промокод уже использован');
            }

        }


        if ($promoCodeModel->only_first_order) {

            try {
                $existFirstOrder = OrderService::findOne([
                    'clientId'     => $clientId,
                    '!orderStatus' => OrderStatus::Canceled,
                ]);
            } catch (\Throwable $e) {
                $existFirstOrder = null;
            }

            if ($existFirstOrder !== null) {
                throw new CRMServiceException('Данный промокод доступен только при первом заказе');
            }
        }

        if ((string)$promoCodeModel->action === PromoCodeAction::BirthDay) {

            /**
             * @var Carbon $clientBirthday
             */
            $clientBirthday = ClientService::findOne(['id' => $clientId])->getRepository()->getBirthDay();

            if (!$clientBirthday) {
                throw new CRMServiceException('Промокод без указания даты рождения недоступен', 423);
            }
            $clientBirthday = Carbon::createFromDate($clientBirthday);

            $range = SettingHelper::getSettingValue('countDayActivePromoCodeBirthday');


            $sub = Carbon::now()
                ->subDays($range['before'])
                ->setHours(0)
                ->setMinutes(0)
                ->setSecond(0);

            $add = Carbon::now()
                ->addDays($range['after'])
                ->setHours(23)
                ->setMinutes(59)
                ->setSecond(59);

            /*$clientBirthday2 = Carbon::createFromDate(
                Carbon::now()->year,
                $clientBirthday->month,
                $clientBirthday->day
            )*/
            $clientBirthday->setYear(date('Y'))
                ->setHours(12)
                ->setMinutes(0)
                ->setSecond(0);


            if (!$clientBirthday->between($sub, $add)) {
                $dateFormat = 'd.m.Y';
                throw new CRMServiceException('Данный промокод недоступен для даты рождения '.$clientBirthday->format($dateFormat) . "(Скидка доступна для дат с {$sub->format($dateFormat)} по {$add->format($dateFormat)})");
            }
        }

        /* if ($promoCodeModel->referrer_client_id === $clientId) {

             throw new CRMServiceException('Данный промокод не доступен');
         }*/

        if ((string)$promoCodeModel->action === PromoCodeAction::FriendPercent && (string)$promoCodeModel->type === PromoCodeType::Personal) {

            try {
                ClientPromoCodeService::findOne([
                    'clientId'    => $clientId,
                    'promoCodeId' => $promoCodeModel->id,
                    'activated'   => false,
                ]);
            } catch (\Throwable $e) {
                throw new CRMServiceException('Данный промокод не доступен');
            }
        }

    }


}
