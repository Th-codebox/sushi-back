<?php

namespace App\Http\Requests\CRM\Setting;

use Illuminate\Foundation\Http\FormRequest;

class  StoreSettingRequest extends FormRequest
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
            'name' => 'required|unique:settings,name',
            'key'  => 'required|unique:settings,key',
        ];
    }
}
