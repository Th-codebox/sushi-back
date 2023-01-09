<?php

namespace App\Http\Resources\CRM;

use App\Models\System\WorkSchedule;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class WorkScheduleResource
 * @package App\Http\Resources\CRM
 */
class WorkScheduleResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var WorkSchedule $item
         */
        $item = $this->resource;

        return [
            'id'        => $item->id,
            'date'      => $item->date,
            'begin'     => $item->begin,
            'end'       => $item->end,
            'shiftTime' => $item->shift_time,
            'user'      => (new UserResource($this->whenLoaded('user'))),
            'workSpace' => (new WorkSpaceResource($this->whenLoaded('workSpace'))),
        ];
    }
}
