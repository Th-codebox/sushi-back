<?php


namespace App\Http\Requests\CRM\Courier;

use App\Enums\CourierOrderPaymentType;
use App\Enums\TransactionPaymentType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionOperationType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ConfirmCancelling
 * @package App\Http\Requests\Courier\OrderCourier
 */
class UpdateTransaction extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'id',
            'userId',
            'price',
            'paymentType',
            'operationType',
            'quantityChecks',
        ];
        return [
            'price' => 'int',
            'quantityChecks' => 'int',
            'paymentType' => 'enum_value:' . TransactionPaymentType::class,
            'operationType' => 'enum_value:' . TransactionOperationType::class,
            '*'     => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимый параметр, выберите один из следующих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
