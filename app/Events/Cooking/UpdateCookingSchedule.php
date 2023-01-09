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

class UpdateCookingSchedule
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

}
