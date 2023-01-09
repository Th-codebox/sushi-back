<?php

namespace App\Http\Requests\CRM\WorkSchedule;

use App\Enums\ShiftTime;
use Illuminate\Foundation\Http\FormRequest;

class CreateTodayWorkSchedule extends FormRequest
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
            'items' => ['required', function ($attribute, $value, $fail) {

                if (is_array($value)) {
                    foreach ($value as $modification) {


                        if (!array_key_exists('workSpaceId', $modification) && (!array_key_exists('isCourier', $modification) && $modification['isCourier'])) {
                            $fail('workSpaceId или isCourier обязательное поле в массивe');
                        }

                        if (!array_key_exists('shiftTime', $modification)) {
                            $fail('shiftTime обязательное поле в массивe');
                        }
                        if (!array_key_exists('userId', $modification)) {
                            $fail('userId обязательное поле в массивe');
                        }
                    }
                }
            }],
            'date'  => ['required', 'date'],
        ];
    }
}
