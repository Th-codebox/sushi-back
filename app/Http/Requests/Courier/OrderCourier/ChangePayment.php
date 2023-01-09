<?php


namespace App\Http\Requests\Courier\OrderCourier;

use App\Enums\CourierOrderPaymentType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ConfirmCancelling
 * @package App\Http\Requests\Courier\OrderCourier
 */
class ChangePayment extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'paymentType'       => 'required|enum_value:' . CourierOrderPaymentType::class,
        ];
    }
}
