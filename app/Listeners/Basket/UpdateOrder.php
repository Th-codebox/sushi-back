<?php

namespace App\Listeners\Basket;

use App\Enums\BasketItemType;
use App\Enums\MenuItemType;
use App\Enums\OrderStatus;
use App\Enums\PromoCodeAction;
use App\Events\Basket\UpdateBasket;
use App\Models\Order\Order;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Order\BasketItemService;
use App\Services\CRM\Order\OrderService;
use Illuminate\Support\Carbon;

class UpdateOrder
{

    /**
     * @param UpdateBasket $event
     */
    public function handle(UpdateBasket $event): void
    {

        try {

            /**
             * @var OrderService $order
             */
            $order = OrderService::findOne(['basketId' => $event->basket->id, '!orderStatus' => [OrderStatus::Completed, OrderStatus::Canceled]]);

            $order->getRepository()->update([
                'clientAddressId'        => $event->basket->client_address_id,
                'filialId'               => $event->basket->filial_id,
                'deliveryType'           => $event->basket->delivery_type,
                'paymentType'            => $event->basket->payment_type,
                'cookingAndDeliveryTime' => $event->basket->cooking_and_delivery_time,
                'totalPrice'             => $event->basket->total_price,
                'discountAmount'         => $event->basket->discount_amount,
            ]);


        } catch (\Throwable $e) {


        }
    }
}
