<?php

namespace App\Events\Order;

use App\Models\Order\Order;
use App\Models\System\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeOrderStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public $user;

    /**
     * ChangeOrderStatus constructor.
     * @param Order $order
     * @param  $user
     */
    public function __construct(Order $order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
