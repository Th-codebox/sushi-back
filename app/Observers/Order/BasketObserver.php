<?php

namespace App\Observers\Order;

use App\Enums\BasketItemType;
use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use App\Enums\PromoCodeAction;
use App\Libraries\Helpers\SettingHelper;
use App\Models\Order\Basket;
use App\Repositories\Order\BasketItemRepository;
use App\Repositories\Order\BasketRepository;
use App\Repositories\Order\OrderRepository;
use App\Services\CRM\Order\BasketItemService;
use App\Services\CRM\Order\BasketService;
use App\Services\CRM\Order\OrderService;

class BasketObserver
{
    /**
     * @param Basket $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function created(Basket $item): void
    {

        try {
            /**
             * @var OrderRepository $previousOrder
             */
            $previousOrder = OrderService::findOne(['clientId' => $item->client_id])->getRepository();

            $previousClientAddressId = $previousOrder->getClientAddressId();
            $previousFilialId = $previousOrder->getFilialId();

        } catch (\Throwable $e) {
            $previousClientAddressId = null;
            $previousFilialId = null;
        }

        $basketRepo = BasketService::findOne(['id' => $item->id])->getRepository();

        $basketRepo->update([
            'previousClientAddressId' => $previousClientAddressId,
            'previousFilialId'        => $previousFilialId,
        ]);
    }

    /**
     * @param Basket $item
     */
    public function updated(Basket $item)
    {
    }




    /**
     * Handle the item "deleted" event.
     *
     * @param Basket $item
     * @return void
     */
    public function deleted(Basket $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param Basket $item
     * @return void
     */
    public function restored(Basket $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param Basket $item
     * @return void
     */
    public function forceDeleted(Basket $item)
    {
        //
    }
}
