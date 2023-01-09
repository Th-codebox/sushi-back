<?php

namespace App\Providers;

use App\Events\Basket\UpdateBasket;
use App\Events\Client\CreateClient;
use App\Events\Cooking\UpdateCookingSchedule;
use App\Events\Order\ChangeOrderStatus;

use App\Events\Order\Custom\OrderCancelEvent;
use App\Events\Order\Custom\OrderCompleteEvent;
use App\Events\Order\Custom\OrderInDeliveryEvent;
use App\Events\Order\Custom\OrderNewEvent;
use App\Events\Order\Custom\OrderPreparingEvent;
use App\Events\Request\CreateRequest;
use App\Listeners\Basket\UpdateOrder;
use App\Listeners\Cooking\ChangeOrderStatusByCookingSchedule;
use App\Listeners\Cooking\CheckToAssembly;
use App\Listeners\Order\Custom\NotifyClientAboutNewOrder;
use App\Listeners\Order\Custom\NotifyClientAboutOrderInDelivery;
use App\Listeners\Order\Custom\PrintColdKitchenCheck;
use App\Listeners\Order\Custom\PrintHotKitchenCheck;
use App\Listeners\Order\Custom\PrintMainCheck;
use App\Listeners\Order\Custom\SendOrderToAtolOnline;
use App\Listeners\Order\Custom\NotificateCourierAboutCanceledOrder;
use App\Listeners\Order\OrderEventsRouter;
use App\Listeners\Basket\CalcItems;
use App\Listeners\Basket\UpdateFreeItems;
use App\Listeners\Client\AddPromoCode;
use App\Listeners\Order\Action;

use App\Listeners\Order\SaveActionLog;
use App\Listeners\Order\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            //SendEmailVerificationNotification::class,
        ],
        ChangeOrderStatus::class => [
            SaveActionLog::class,
            OrderEventsRouter::class,
            Notification::class,
            Action::class,
        ],
        UpdateBasket::class => [
            CalcItems::class,
            UpdateFreeItems::class,
            UpdateOrder::class,
        ],
        CreateClient::class => [
            AddPromoCode::class,
        ],
        UpdateCookingSchedule::class => [
            ChangeOrderStatusByCookingSchedule::class,
        ],
        CreateRequest::class => [
            \App\Listeners\Request\Notification::class,
        ],

        /***** ЗАКАЗЫ *****/

        /* Новый заказ */
        OrderNewEvent::class => [
            NotifyClientAboutNewOrder::class
        ],

        /* Заказ доставляется */
        OrderInDeliveryEvent::class => [
            NotifyClientAboutOrderInDelivery::class
        ],

        /* Заказ готовится */
        OrderPreparingEvent::class => [
            PrintMainCheck::class,
            PrintColdKitchenCheck::class,
            PrintHotKitchenCheck::class
        ],

        /* Завершение заказа */
        OrderCompleteEvent::class => [
            SendOrderToAtolOnline::class
        ],

        /* Отмена заказа */
        OrderCancelEvent::class => [
            NotificateCourierAboutCanceledOrder::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
