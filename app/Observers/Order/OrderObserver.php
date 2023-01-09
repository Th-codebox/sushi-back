<?php

namespace App\Observers\Order;


use App\Enums\OrderStatus;
use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Models\Order\Order;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\Store\ClientPromoCodeService;
use Illuminate\Support\Carbon;

class OrderObserver
{
    /**
     * @param Order $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function created(Order $item)
    {
        /**
         * @var Order $order
         */
        $order = OrderService::findOne(['id' => $item->id])->getRepository()->getModel();

        if ($order->basket->clientPromoCode) {

            $clientPromoCode = ClientPromoCodeService::findOne(['id' => $order->basket->clientPromoCode->id]);

            $clientPromoCode->edit([
                'orderId' => $order->id,
            ]);
        }

    }

    /**
     * @param Order $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function updated(Order $item)
    {


    }

    public function saveActiveLog()
    {

    }

    /**
     * Handle the item "deleted" event.
     *
     * @param Order $item
     * @return void
     */
    public function deleted(Order $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param Order $item
     * @return void
     */
    public function restored(Order $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param Order $item
     * @return void
     */
    public function forceDeleted(Order $item)
    {
        //
    }
}
