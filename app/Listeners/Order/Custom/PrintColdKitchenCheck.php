<?php

namespace App\Listeners\Order\Custom;

use App\Enums\CheckType;
use App\Events\Order\Custom\OrderPreparingEvent;
use App\Libraries\Printers\PrintService;
use App\Models\Order\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class PrintColdKitchenCheck {
    /*implements ShouldQueue
{
    use InteractsWithQueue;

    public $afterCommit = true;*/

    private PrintService $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    /**
     * Handle the event.
     *
     * @param  OrderPreparingEvent  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var Order $order */
       $order = $event->order;

       $this->printService->printOrderCheck($order, CheckType::Cold());
    }
}
