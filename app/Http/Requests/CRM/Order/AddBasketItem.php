<?php


namespace App\Http\Requests\CRM\Order;

use App\Enums\BasketItemType;
use Illuminate\Foundation\Http\FormRequest;

class AddBasketItem extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'menuItemId',
            'modificationId',
            'subMenuItemId',
            'type',
            'comment',
        ];

        return [
            'menuItemId'     => 'required|numeric|exists:menu_items,id,deleted_at,NULL',
            'subMenuItemId'  => 'nullable|numeric|exists:menu_items,id,deleted_at,NULL',
            'modificationId' => 'nullable|numeric|exists:modification_menu_item,id',
            'comment'        => 'nullable|string',
            'type'           => [
                'nullable',
                'enum_value:' . BasketItemType::class,
                function ($attribute, $value, $fail) use ($allowFields) {
                    $data = $this->request->all();

                    if ($data['type'] === BasketItemType::Construct && !(array_key_exists('subMenuItemId', $data) && is_numeric($data['subMenuItemId']))) {
                        return $fail('Если эллемент корзины конструктор - должен быть задан subMenuItemId для второй половинки');
                    }
                }],
            '*'              => function ($attribute, $value, $fail) use ($allowFields) {

                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
