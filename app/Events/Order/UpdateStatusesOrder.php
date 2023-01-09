<?php

namespace App\Events\Order;

use App\Http\Resources\CRM\OrderResource;
use App\Models\Order\Order;
use App\Services\CRM\Order\OrderService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateStatusesOrder implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Order $order;


    /**
     * UpdateStatusesOrder constructor.
     * @param Order $order
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('orders.filial.' . $this->order->filial_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => (new OrderService(null))->getOrderStatusesWithCount()
        ];
    }
}
