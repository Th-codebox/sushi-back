<?php


namespace App\Http\Requests\Courier\OrderCourier;

use App\Enums\TransactionOperationType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ConfirmCancelling
 * @package App\Http\Requests\Courier\OrderCourier
 */
class TransactionStart extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowValues = [
            'send',
            'receive',
        ];
        return [
            'type' => [
                'required',
                'string',
                'enum_value:' . TransactionOperationType::class,
            ],
        ];
    }
}
