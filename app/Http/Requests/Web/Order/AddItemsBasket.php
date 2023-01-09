<?php


namespace App\Http\Requests\Web\Order;

use App\Enums\BasketItemType;
use App\Models\Order\Basket;
use Illuminate\Foundation\Http\FormRequest;

class AddItemsBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'items',
            'persons',
            'uuid',
        ];
        return [
            'items' => [
                'array',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return $fail('Должен быть многомерном массивом с обьектами');
                    }

                    foreach ($value as $item) {

                        if (array_key_exists('type',$item) && $item['type'] === BasketItemType::Construct && !(array_key_exists('subMenuItemId', $item) && is_numeric($item['subMenuItemId']))) {
                            return $fail('Если эллемент корзины конструктор - должен быть задан subMenuItemId для второй половинки');
                        }

                        if (!array_key_exists('menuItemId', $item)) {
                            return $fail('menuItemId  обязательное поле в массиве c эллементами корзины');
                        }
                        if (!array_key_exists('quantity', $item)) {
                            return $fail('quantity  обязательное поле в массиве c эллементами корзины');
                        }
                    }
                },
            ],
            '*'     => function ($attribute, $value, $fail) use ($allowFields) {
                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимое значение, выберите одно из слудющих значений:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
