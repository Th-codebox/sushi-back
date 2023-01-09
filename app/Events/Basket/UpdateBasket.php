<?php

namespace App\Events\Basket;

use App\Models\Order\Basket;
use App\Models\System\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateBasket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Basket $basket;
    public  $user;

    /**
     * ChangeBasketStatus constructor.
     * @param Basket $basket
     * @param  $user
     */
    public function __construct(Basket $basket, $user)
    {

        $this->basket = $basket;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      //  return new PrivateChannel('channel-name');
    }
}
