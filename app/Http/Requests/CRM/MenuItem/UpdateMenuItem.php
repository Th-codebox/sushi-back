<?php


namespace App\Http\Requests\CRM\MenuItem;

use App\Enums\StickerMarketing;
use App\Enums\StickerType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateMenuItem
 * @package App\Http\Requests\CRM\MenuItem
 */
class UpdateMenuItem extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price'           => 'numeric',
            'slug'            => 'unique:menu_items,slug,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id,deleted_at,NULL',
            'technicalCardId' => 'numeric|exists:technical_cards,id,deleted_at,NULL',
            'StickerType'      => 'enum_value:' . StickerType::class,
            'StickerMarketing' => 'enum_value:' . StickerMarketing::class,
            'modifications' =>
                [
                    'array',
                    function ($attribute, $value, $fail) {

                        if (is_array($value)) {
                            foreach ($value as $modification) {

                                if (!array_key_exists('modificationId', $modification)) {
                                    $fail('modificationId обязательное поле в массиве с модификациями');
                                }

                            }
                        }

                    },
                ],
            'bundleItems' =>
                [
                    'array',
                    function ($attribute, $value, $fail) {

                        if (is_array($value)) {
                            foreach ($value as $modification) {

                                if (!array_key_exists('menuItemId', $modification)) {
                                    $fail('menuItemId обязательное поле в массиве с эллементами набора');
                                }
                            }
                        }

                    },
                ],
        ];
    }
}
