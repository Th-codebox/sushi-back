<?php

namespace App\Events\Request;


use App\Models\System\Request;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Request $request;

    /**
     * CreateRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
