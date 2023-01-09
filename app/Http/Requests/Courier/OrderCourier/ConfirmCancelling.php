<?php


namespace App\Http\Requests\Courier\OrderCourier;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ConfirmCancelling
 * @package App\Http\Requests\Courier\OrderCourier
 */
class ConfirmCancelling extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'orderIds'       => 'required|array',
        ];
    }
}
