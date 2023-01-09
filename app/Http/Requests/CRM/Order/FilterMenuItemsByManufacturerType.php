<?php

namespace App\Http\Requests\CRM\Order;

use App\Enums\CheckType;
use App\Enums\ManufacturerType;
use BenSampo\Enum\Enum;
use Illuminate\Foundation\Http\FormRequest;

class FilterMenuItemsByManufacturerType extends FormRequest
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
            'type' => 'required|enum_value:' . ManufacturerType::class
        ];
    }

    public function getManufacturerType(): ManufacturerType
    {
        return new ManufacturerType($this->input('type'));
    }
}
