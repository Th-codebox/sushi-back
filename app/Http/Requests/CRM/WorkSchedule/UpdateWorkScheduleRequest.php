<?php

namespace App\Http\Requests\CRM\WorkSchedule;

use App\Enums\ShiftTime;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkScheduleRequest extends FormRequest
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
            'date'      => 'date',
            'begin'     => 'date_format:H:i',
            'end'       => 'date_format:H:i',
            'shiftTime' => 'enum_value:' . ShiftTime::class,
            'workSpace' => 'numeric|exists:work_spaces,id',
        ];
    }
}
