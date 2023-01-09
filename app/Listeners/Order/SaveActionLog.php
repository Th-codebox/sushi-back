<?php

namespace App\Listeners\Order;

use App\Enums\OrderStatus;
use App\Events\Order\ChangeOrderStatus;

class SaveActionLog
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
     * @param ChangeOrderStatus $event
     */
    public function handle(ChangeOrderStatus $event)
    {

    }
}
