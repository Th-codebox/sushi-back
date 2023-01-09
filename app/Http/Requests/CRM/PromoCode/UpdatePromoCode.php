<?php


namespace App\Http\Requests\CRM\PromoCode;

use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdatePromoCode
 * @package App\Http\Requests\CRM\PromoCode
 */
class UpdatePromoCode extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                       => 'string',
            'code'                       => 'string',
            'action'                     => 'enum_value:' . PromoCodeAction::class,
            'type'                       => 'enum_value:' . PromoCodeType::class,
            'salePercent'                => 'nullable|numeric',
            'saleSubtraction'            => 'nullable|numeric',
            'saleMenuItemId'             => 'nullable|numeric|exists:menu_items,id',
            'saleModificationMenuItemId' => 'nullable|numeric|exists:modification_menu_item,id',
        ];
    }
}
