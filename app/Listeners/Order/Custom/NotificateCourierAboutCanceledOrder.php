<?php

namespace App\Listeners\Order\Custom;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Events\Order\ChangeOrderStatus;
use App\Events\Order\Custom\OrderCancelEvent;
use App\Events\Order\Custom\OrderCompleteEvent;
use App\Models\Order\Order;
use App\Notifications\Courier\OrderCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificateCourierAboutCanceledOrder
{
    /**
     * Handle the event.
     *
     * @param  OrderCancelEvent  $event
     * @return void
     */
    public function handle(OrderCancelEvent $event)
    {
        /** @var Order $order */
       $order = $event->order;

       if ($order->delivery_type == DeliveryType::Delivery && $order->courier) {
           $order->courier->notify(new OrderCanceled($order));
       }
    }
}
