<?php

namespace App\Http\Requests\CRM\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRole extends FormRequest
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
            'name'       => 'unique:roles,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'permissions' => [
                'array',
                function ($attribute, $value, $fail) {

                    if(!is_array($value)) {

                        return $fail('Массив должен содержать обьекты с полями permissionId,type(необязательный параметр)');
                    }
                    foreach ($value as $permission) {
                        if (!array_key_exists('permissionId', $permission)) {
                            return $fail('permissionId обязательное поле в массиве с правами');
                        }
                    }
                },
            ],
        ];
    }
}
