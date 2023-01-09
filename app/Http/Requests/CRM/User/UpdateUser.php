<?php


namespace App\Http\Requests\CRM\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {

        return [
            'phone'    => 'phone:RU|unique:users,phone,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'email'    => 'email',
            'password' => 'min:6',
            'roleId'   => 'numeric|exists:roles,id,deleted_at,NULL',
            'filials' => [
                'array',
                function ($attribute, $value, $fail) {

                    if(!is_array($value)) {

                        return $fail('Массив должен содержать обьекты с полями filialId,main(необязательный параметр)');
                    }
                    foreach ($value as $permission) {
                        if (!array_key_exists('filialId', $permission)) {
                            return $fail('filialId обязательное поле в массиве');
                        }
                    }
                },
            ],
            'docs' => [
                'array',
                function ($attribute, $value, $fail) {

                    if(!is_array($value)) {

                        return $fail('Массив должен содержать обьекты с полями path,name');
                    }
                    foreach ($value as $permission) {
                        if (!array_key_exists('path', $permission)) {
                            return $fail('path обязательное поле в массиве');
                        }
                        if (!array_key_exists('name', $permission)) {
                            return $fail('name обязательное поле в массиве');
                        }
                    }
                },
            ],
        ];
    }
}
