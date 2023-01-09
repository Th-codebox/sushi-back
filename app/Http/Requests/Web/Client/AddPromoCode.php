<?php


namespace App\Http\Requests\Web\Client;

use Illuminate\Foundation\Http\FormRequest;

class AddPromoCode extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'promoCode',
            'uuid',

        ];
        return [
            'promoCode'  => 'string',
            '*'     => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
