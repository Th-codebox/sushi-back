<?php

namespace App\Http\Requests\CRM\Reports;

use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;

class DateInterval extends FormRequest
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
            'from' => 'required | date_format:Y-m-d',
            'to' => 'required | date_format:Y-m-d',
            'filialId' => 'nullable | numeric'
        ];
    }

    public function getPeriod(): CarbonPeriod
    {
        return CarbonPeriod::create($this->from, $this->to . "23:59:59");
    }

    public function getFilialId()
    {
        return $this->input('filialId');
    }
}
