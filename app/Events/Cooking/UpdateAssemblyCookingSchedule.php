<?php

namespace App\Events\Cooking;

use App\Http\Resources\CRM\CookingScheduleResource;
use App\Http\Resources\CRM\OrderResource;
use App\Models\System\CookingSchedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAssemblyCookingSchedule implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CookingSchedule $cookingSchedule;
    public  $user;

    /**
     * UpdateCookingSchedule constructor.
     * @param CookingSchedule $cookingSchedule
     * @param $user
     */
    public function __construct(CookingSchedule $cookingSchedule, $user)
    {

        $this->cookingSchedule = $cookingSchedule;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('cooking.cookingSchedule.filial.' . $this->cookingSchedule->order->filial_id);
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => []
                //json_decode((new CookingScheduleResource($this->cookingSchedule))->toJson())
        ];
    }
}
