<?php

namespace App\Listeners\Order\Custom;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Events\Order\ChangeOrderStatus;
use App\Events\Order\Custom\OrderCancelEvent;
use App\Events\Order\Custom\OrderCompleteEvent;
use App\Events\Order\Custom\OrderInDeliveryEvent;
use App\Models\Order\Order;
use App\Notifications\Client\NewOrder;
use App\Notifications\Client\OrderInDelivery;
use App\Notifications\Courier\OrderCanceled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyClientAboutOrderInDelivery
{
    /**
     * Отправляем клиенту смс о передаче заказ курьеру.
     *
     * @param  OrderInDeliveryEvent  $event
     * @return void
     */
    public function handle(OrderInDeliveryEvent $event)
    {
        /** @var Order $order */
       $order = $event->order;

        $order->client->notify(new OrderInDelivery($order));
    }
}
