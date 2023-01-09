<?php

namespace App\Events\User;

use App\Http\Resources\CRM\OrderResource;
use App\Http\Resources\CRM\TransactionResource;
use App\Models\System\Transaction;
use App\Models\System\User;
use App\Services\CRM\System\FilialCashBoxService;
use App\Services\CRM\System\WorkScheduleService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CreateTransaction implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Transaction $transaction;

    private array $broadCastingsArray;

    /**
     * CreateTransaction constructor.
     * @param Transaction $transaction
     * @param $user
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function __construct(Transaction $transaction,User $user)
    {
        $this->transaction = $transaction;

        $todayWorkSchedules = WorkScheduleService::findList(['filialId' => $user->actualFilial->id, 'day' => 'today', 'role' => 'manager']);

        foreach ($todayWorkSchedules as $workSchedule) {
            /**
             * @var WorkScheduleService $workSchedule
             */
            $this->broadCastingsArray[] = new PrivateChannel('user.' . $workSchedule->getRepository()->getModel()->user_id);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return $this->broadCastingsArray;
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => json_decode((new TransactionResource($this->transaction))->toJson()),
        ];
    }
}
