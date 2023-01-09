<?php


namespace App\Http\Requests\Web\Order;

use App\Enums\BasketItemType;
use Illuminate\Foundation\Http\FormRequest;

class ChangeModification extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'modificationId',
            'uuid',
        ];

        return [
            'modificationId' => 'nullable|numeric|exists:modification_menu_item,id',
            ' * ' => function ($attribute, $value, $fail) use ($allowFields) {

                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
