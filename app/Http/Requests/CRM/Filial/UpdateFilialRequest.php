<?php

namespace App\Http\Requests\CRM\Filial;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFilialRequest extends FormRequest
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
            'name'       => 'unique:filials,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
        ];
    }
}
