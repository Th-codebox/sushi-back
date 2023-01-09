<?php


namespace App\Http\Requests\Web\Order;

use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipientBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $allowFields = [
            'recipientName',
            'recipientPhone',
            'uuid',
        ];

        return [
            'recipientName'  => 'required|string',
            'recipientPhone' => 'required|string',
            '*'              => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
