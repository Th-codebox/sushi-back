<?php


namespace App\Http\Requests\CRM\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Get
 * @package App\Http\Requests\Courier\Information
 */
class GetTimeTable extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date'       => 'nullable|int',
        ];
    }
}
