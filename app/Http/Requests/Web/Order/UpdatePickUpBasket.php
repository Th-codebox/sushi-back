<?php


namespace App\Http\Requests\Web\Order;

use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePickUpBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $allowFields = [
            'filialId',
            'uuid',
        ];

        return [
            'filialId' => 'required|numeric',
            '*'        => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
