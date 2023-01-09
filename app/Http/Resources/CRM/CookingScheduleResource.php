<?php

namespace App\Http\Resources\CRM;

use App\Models\System\CookingSchedule;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CookingScheduleResource
 * @package App\Http\Resources\CRM
 */
class CookingScheduleResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var CookingSchedule $item
         */

        $item->calcDeadLine();

        return [
            'order'   => (new OrderResource($this->whenLoaded('order'))),
            'beginAt' => $item->hot_is_completed && $item->cold_is_completed && $item->time_begin_assembly ? $item->time_begin_assembly->toDateTimeString() : $item->created_at->toDateTimeString(),
        ];
    }
}
