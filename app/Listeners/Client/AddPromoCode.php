<?php

namespace App\Listeners\Client;

use App\Enums\OrderStatus;
use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Events\Client\CreateClient;
use App\Events\Order\ChangeOrderStatus;
use App\Libraries\Helpers\SettingHelper;
use App\Models\System\Client;
use App\Services\CRM\Store\ClientPromoCodeService;
use App\Services\CRM\Store\PromoCodeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class AddPromoCode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CreateClient $event
     * @throws \App\Repositories\RepositoryException
     */
    public function handle(CreateClient $event)
    {
        $menuItem = SettingHelper::getSettingValue('saleWelcomeItem')['saleMenuItemId'] ?? null;


        if ($menuItem) {
            try {
                PromoCodeService::findOne(['code' => $event->client->referral_promo_code]);
            } catch (ModelNotFoundException $e) {
                PromoCodeService::add([
                    'name'                       => SettingHelper::getSettingValue('namePromoActionInvite'),
                    'description'                => 'Получите вкуснейший "Fox-ролл" в подарок',
                    'code'                       => $event->client->referral_promo_code,
                    'referrerClientId'           => $event->client->id,
                    'type'                       => PromoCodeType::Personal,
                    'action'                     => PromoCodeAction::DishGift,
                    'status'                     => true,
                    'onlyFirstOrder'             => true,
                    'saleModificationMenuItemId' => SettingHelper::getSettingValue('saleWelcomeItem')['saleModificationMenuItemId'] ?? null,
                    'saleMenuItemId'             => $menuItem,
                ]);
            }
        }

        if ($event->client->birthday) {

            $promoCode = PromoCodeService::findOne(['action' => PromoCodeAction::BirthDay]);

            try {
                $oldPromoCodes = ClientPromoCodeService::findList([
                    'promoCodeId' => $promoCode->getRepository()->getModel()->id,
                    'clientId'    => $event->client->id,
                    'activated'   => false,
                ]);

                foreach ($oldPromoCodes as $oldPromoCode) {
                    $oldPromoCode->delete();
                }

            } catch (\Throwable $e) {

            }


            $range = SettingHelper::getSettingValue('countDayActivePromoCodeBirthday');
            $clientBirthday = Carbon::createFromDate($event->client->birthday);

            $birthDayNow = Carbon::createFromFormat('Y-m-d', Carbon::now()->year . '-' . $clientBirthday->month . '-' . $clientBirthday->day);

            if ($birthDayNow->unix() < Carbon::now()->unix()) {
                $birthDayNow = Carbon::createFromFormat('Y-m-d', Carbon::now()->addYear()->year . '-' . $clientBirthday->month . '-' . $clientBirthday->day);
            }


            $deadLine = $birthDayNow->addDays($range['after'])->toDateString();
            $dateBegin = $birthDayNow->subDays($range['after'])->subDays($range['before'])->toDateString();


            ClientPromoCodeService::add([
                'promoCodeId' => $promoCode->getRepository()->getModel()->id,
                'clientId'    => $event->client->id,
                'activate'    => false,
                'deadLine'    => $deadLine,
                'dateBegin'   => $dateBegin,
            ]);
        }

    }
}
