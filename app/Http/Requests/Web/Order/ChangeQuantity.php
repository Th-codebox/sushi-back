<?php


namespace App\Http\Requests\Web\Order;

use App\Enums\BasketItemType;
use Illuminate\Foundation\Http\FormRequest;

class ChangeQuantity extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'type',
            'uuid',
        ];

        return [
            'type' => 'required|string',
            ' * ' => function ($attribute, $value, $fail) use ($allowFields) {

                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
