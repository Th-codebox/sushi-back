<?php

namespace App\Http\Resources\Courier;

use App\Enums\ShiftTime;
use App\Models\System\WorkSchedule;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;


/**
 * Class CourierWorkScheduleResource
 * @package App\Http\Resources\Courier
 */
class CourierWorkScheduleResource extends JsonResource
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
        date_default_timezone_set('UTC');
        $shiftTime = 'offDay'; //на всякий случай
        $begin = '11:00';
        $end = '23:00';

        switch ($item->shift_time) {
            case ShiftTime::First:
                $shiftTime = 'half';
                $begin = '11:00';
                $end = '17:00';
                break;

            case ShiftTime::Second:
                $shiftTime = 'half';
                $begin = '17:00';
                $end = '23:00';
                break;

            case ShiftTime::Full:
                $shiftTime = 'full';
                $begin = '11:00';
                $end = '23:00';
                break;

            case ShiftTime::OffDay:
                $shiftTime = 'offDay';
                $begin = '11:00';
                $end = '23:00';
        }
        date_default_timezone_set('UTC');
        return [
            'id'        => (int)$item->id,
            'date'      => $item->date->unix(),
            'begin'     => (string)$begin,
            'end'       => (string)$end,
            'shiftTime' => (string)$shiftTime,
        ];
    }
}
