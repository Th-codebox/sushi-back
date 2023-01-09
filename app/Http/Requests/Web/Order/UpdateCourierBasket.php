<?php


namespace App\Http\Requests\Web\Order;

use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourierBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $allowFields = [
            'clientAddressId',
            'dateDelivery',
            'timeDelivery',
            'toDatetime',
            'uuid',
        ];
        return [
            'clientAddressId' => 'required|numeric|exists:client_address,id,deleted_at,NULL',
            'dateDelivery'    => 'date',
            'timeDelivery'    => 'string',
            'toDatetime'      => 'bool',
            '*'               => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
