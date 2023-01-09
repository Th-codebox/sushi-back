<?php

namespace App\Listeners\Order;

use App\Enums\OrderStatus;
use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Events\Order\ChangeOrderStatus;
use App\Libraries\Helpers\SettingHelper;
use App\Models\Order\Order;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\Store\ClientPromoCodeService;
use App\Services\CRM\Store\PromoCodeService;
use Illuminate\Support\Carbon;

class Action
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
     * @param ChangeOrderStatus $event
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function handle(ChangeOrderStatus $event)
    {

        /**
         * @var Order $order
         */
        $order = OrderService::findOne(['id' => $event->order->id])->getRepository()->getModel();

        if ((string)$event->order->order_status === OrderStatus::Completed) {

            if($order->basket->clientPromoCode) {
                $promoCode = $order->basket->clientPromoCode->promoCode;


                if ((string)$promoCode->type === PromoCodeType::Personal
                    && (string)$promoCode->action === PromoCodeAction::DishGift
                    && $promoCode->referrer_client_id) {

                    $promoCodeFriendSaleId = PromoCodeService::findOne([
                        'action' => PromoCodeAction::FriendPercent
                    ]);

                    ClientPromoCodeService::add([
                        'promoCodeId' => $promoCodeFriendSaleId->getRepository()->getModel()->id,
                        'clientId'    => $promoCode->referrerClient->id,
                    ]);
                }

                if ((string)$promoCode->type === PromoCodeType::Personal
                    && (string)$promoCode->action === PromoCodeAction::BirthDay)
                {

                    $range = SettingHelper::getSettingValue('countDayActivePromoCodeBirthday');

                    $clientBirthday = Carbon::createFromDate($event->order->client->birthday);

                    $birthDayNow = Carbon::createFromFormat(
                        'Y-m-d',
                        Carbon::now()->year . '-' . $clientBirthday->month . '-' . $clientBirthday->day
                    );

                    if($birthDayNow->unix() < Carbon::now()->unix()) {
                        $birthDayNow = Carbon::createFromFormat(
                            'Y-m-d',
                            Carbon::now()->subYear()->year . '-' . $clientBirthday->month . '-' . $clientBirthday->day
                        );
                    }

                    $deadLine = $birthDayNow->addDays($range['after'])->toDateString();
                    $dateBegin = $birthDayNow->subDays($range['after'])->subDays($range['before'])->toDateString();


                    ClientPromoCodeService::add([
                        'promoCodeId' => $promoCode->id,
                        'clientId'    => $event->order->client->id,
                        'activate'    => false,
                        'deadLine'    => $deadLine,
                        'dateBegin'   => $dateBegin,
                    ]);
                }
            }
        }
        if ((string)$event->order->order_status === OrderStatus::Canceled && $order->basket->clientPromoCode) {

            ClientPromoCodeService::findOne([
                'orderId' => $order->id,
            ])->edit([
                'activated'   => false,
                'activatedAt' => null,
                'orderId'     => null,
            ]);
        }
    }
}
