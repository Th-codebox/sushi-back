<?php

namespace App\Http\Requests\CRM\WorkSchedule;

use App\Enums\ShiftTime;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'      => 'required|date',
            'begin'     => 'required|date_format:H:i',
            'end'       => 'required|date_format:H:i',
            'shiftTime' => 'required|enum_value:' . ShiftTime::class,
            'userId'    => 'required|exists:users,id',
            'workSpaceId' => 'required|exists:work_spaces,id',
        ];
    }
}
