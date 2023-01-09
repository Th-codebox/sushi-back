<?php

namespace App\Events\Client;

use App\Models\Order\Basket;
use App\Models\System\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateClient
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Client $client;

    /**
     * CreateClient constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
