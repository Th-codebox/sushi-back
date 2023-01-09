<?php

namespace App\Listeners\Order;

use App\Enums\OrderStatus;
use App\Events\Order\ChangeOrderStatus;
use App\Events\Order\Custom\OrderCancelEvent;
use App\Events\Order\Custom\OrderCompleteEvent;
use App\Events\Order\Custom\OrderInDeliveryEvent;
use App\Events\Order\Custom\OrderNewEvent;
use App\Events\Order\Custom\OrderPreparingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderEventsRouter
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
     * Handle the event.
     *
     * @param  ChangeOrderStatus  $event
     * @return void
     */
    public function handle(ChangeOrderStatus $event)
    {


       switch ($event->order->order_status) {

           /* Новый заказ */
           case OrderStatus::New:
               OrderNewEvent::dispatch($event->order);
               break;

           /* Заказ готовится */
           case OrderStatus::Preparing:
               OrderPreparingEvent::dispatch($event->order);
               break;

           /* Заказ доставляется */
           case OrderStatus::InDelivery:
               OrderInDeliveryEvent::dispatch($event->order);
               break;

           /* Завершение заказа */
           case OrderStatus::Completed:
               OrderCompleteEvent::dispatch($event->order);
               break;

           /* Отмена заказа */
           case OrderStatus::Canceled:
               OrderCancelEvent::dispatch($event->order);
               break;


       }
    }
}
