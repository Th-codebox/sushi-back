<?php


namespace App\Http\Requests\Web\Order;

use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'persons',
            'comment',
            'clientAddressId',
            'commentForCourier',
            'toDatetime',
            'uuid',
        ];
        return [
            'persons'           => 'nullable|numeric',
            'comment'           => 'nullable|string',
            'commentForCourier' => 'nullable|string',
            'clientAddressId'   => 'numeric|exists:client_address,id',
            'dateDelivery'      => 'date',
            'timeDelivery'      => 'string',
            'toDatetime'        => 'bool',
            '*'                 => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
