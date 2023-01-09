<?php

namespace App\Listeners\Order\Custom;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Events\Order\ChangeOrderStatus;
use App\Events\Order\Custom\OrderCancelEvent;
use App\Events\Order\Custom\OrderCompleteEvent;
use App\Events\Order\Custom\OrderInDeliveryEvent;
use App\Events\Order\Custom\OrderNewEvent;
use App\Models\Order\Order;
use App\Notifications\Client\NewOrder;
use App\Notifications\Courier\OrderCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyClientAboutNewOrder
{
    /**
     * Отправляем клиенту смс об новом заказе.
     *
     * @param  OrderNewEvent  $event
     * @return void
     */
    public function handle(OrderNewEvent $event)
    {
        /** @var Order $order */
       $order = $event->order;

        $order->client->notify(new NewOrder($order));
    }
}
