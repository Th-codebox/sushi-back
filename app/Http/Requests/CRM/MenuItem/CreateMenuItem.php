<?php


namespace App\Http\Requests\CRM\MenuItem;

use App\Enums\StickerMarketing;
use App\Enums\StickerType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateMenuItem
 * @package App\Http\Requests\CRM\MenuItem
 */
class CreateMenuItem extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'            => 'required',
            'price'           => 'numeric',
            'slug'            => 'unique:menu_items,slug,NULL,id,deleted_at,NULL',
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
