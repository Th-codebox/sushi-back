<?php

namespace App\Events\Phone;

use App\Models\System\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCallEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public string $callId;

    public int $accountId;

    public string $clientPhone;
    public string $incomingPhone;

    public bool $isActiveClient = false;
    public string $clientName = '';
    public int $clientId = 0;
    public bool $hasOrdersToday = false;

    /** @var User[] $users */
    protected $users;


    public function __construct(
        string $callId,
        int $accountId,
        string $clientPhone,
        string $incomingPhone,
        bool $isActiveClient = false,
        string $clientName = '',
        int $clientId = 0,
        bool $hasOrdersToday = false,
        array $users = []
    )
    {
        $this->callId = $callId;
        $this->accountId = $accountId;
        $this->clientPhone = $clientPhone;
        $this->incomingPhone = $incomingPhone;
        $this->isActiveClient = $isActiveClient;
        $this->clientName = $clientName;
        $this->clientId = $clientId;
        $this->hasOrdersToday = $hasOrdersToday;
        $this->users = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [
            new Channel('phone.account.'.$this->accountId)
        ];

        foreach ($this->users as $user) {
            $channels[] = new Channel("events.user.{$user->id}");
        }
        #TODO PrivateChannel для авторизации в канале
        //return new PrivateChannel('phone.account.'.$this->accountId);urn

        return $channels;
    }
}
