<?php

namespace App\Http\Requests\CRM\Printing;

use App\Enums\CheckType;
use App\Enums\ManufacturerType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PrintOrderCheck
 * @package App\Http\Requests\CRM\Printing
 *
 * @property CheckType $type;
 * @property int $orderId;
 * @property int $basketItemId;
 * @property int $menuItemId;
 */
class PrintSticker extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|enum_value:' . ManufacturerType::class,
            'orderId' => 'required|numeric|exists:orders,id',
            'basketItemId' => 'required|numeric',
            'menuItemId' => 'required|numeric',
        ];
    }
}
