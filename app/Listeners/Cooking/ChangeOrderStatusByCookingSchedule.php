<?php

namespace App\Listeners\Cooking;

use App\Enums\OrderStatus;
use App\Events\Cooking\UpdateCookingSchedule;
use App\Events\Order\ChangeOrderStatus;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\CookingScheduleService;
use Illuminate\Support\Carbon;

class ChangeOrderStatusByCookingSchedule
{
    /**
     * Create the event listener.
     *
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * @param UpdateCookingSchedule $event
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function handle(UpdateCookingSchedule $event)
    {

        if ($event->cookingSchedule->hot_is_completed && $event->cookingSchedule->cold_is_completed && $event->cookingSchedule->assembly_is_completed) {
            OrderService::findOne(['id' => $event->cookingSchedule->order_id])->edit(['orderStatus' => OrderStatus::ReadyForIssue]);
        } elseif ($event->cookingSchedule->hot_is_completed && $event->cookingSchedule->cold_is_completed) {
            OrderService::findOne(['id' => $event->cookingSchedule->order_id])->edit(['orderStatus' => OrderStatus::Assembly]);
            CookingScheduleService::findOne(['id' => $event->cookingSchedule->id])->getRepository()->update(['timeBeginAssembly' => Carbon::now()]);
        }
    }
}
