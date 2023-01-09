<?php


namespace App\Http\Requests\Web\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentTypeBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'paymentType',
            'clientMoney',
            'uuid',
        ];

        return [
            'paymentType'   => 'required|enum_value:' . PaymentType::class,
            'clientMoney' => 'nullable|numeric',
            '*'             => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
