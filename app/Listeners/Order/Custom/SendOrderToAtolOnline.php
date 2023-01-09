<?php

namespace App\Listeners\Order\Custom;

use App\Events\Order\Custom\OrderCompleteEvent;

use App\Libraries\Atol\AtolApiClient;
use App\Models\Order\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Log;


class SendOrderToAtolOnline  //implements ShouldQueue
{
    //use InteractsWithQueue;

    public $afterCommit = true;

    private AtolApiClient $atolApiClient;

    public function __construct(AtolApiClient $atolApiClient)
    {
        $this->atolApiClient = $atolApiClient;
    }

    /**
     * Handle the event.
     *
     * @param OrderCompleteEvent $event
     * @return void
     */
    public function handle(OrderCompleteEvent $event)
    {

        /** @var Order $order */
        $order = $event->order;

        /* Заказы из агрегаторов в атол не отправляем */
        if ($order->basket->isSourceAggregator()) return;

        try {
            $this->atolApiClient->sendOrder($order);
        } catch (\Exception $e) {
            Log::channel('atol_errors')->error('Atol Exception', [
                'message' => $e->getMessage(),
            ]);
        }

    }
}
