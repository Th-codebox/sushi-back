<?php


namespace App\Http\Requests\Web\Client;

use Illuminate\Foundation\Http\FormRequest;

class EditClient extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'name',
            'email',
            'birthday',
            'uuid',

        ];
        return [
            'name'  => 'string',
            'email' => 'email',
            'birthday' => 'date',
            '*'     => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
