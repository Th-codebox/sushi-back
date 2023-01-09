<?php


namespace App\Http\Requests\CRM\Basket;


use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'paymentPhone',
            'promoCode',
            'persons',
            'comment',
            'commentForCourier',
            'ip',
            'noCall',
            'clientSource',
            'clientAddressId',
            'dateDelivery',
            'timeDelivery',
            'timeInDelivery',
            'cookingAndDeliveryTime',
            'paymentType',
            'clientMoney',
            'filialId',
            'items',
        ];
        return [
            'paymentPhone'           => 'string|phone:RU',
            'promoCode'              => 'string',
            'persons'                => 'numeric',
            'comment'                => 'string',
            'commentForCourier'      => 'string',
            'clientSource'           => 'string',
            'ip'                     => 'string',
            'noCall'                 => 'bool',
            'clientAddressId'        => 'numeric|exists:client_address,id',
            'dateDelivery'           => 'date',
            'timeDelivery'           => 'string',
            'timeInDelivery'         => 'string',
            'cookingAndDeliveryTime' => 'numeric',
            'paymentType'            => 'enum_value:' . PaymentType::class,
            'clientMoney'            => 'numeric',
            'filialId'               => 'numeric|exists:filials,id',
            'promoCodeId'            => 'numeric|exists:promo_codes,id',
            'toDatetime'             => 'bool',
            'items'                  => [
                'array',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return $fail('Должен быть многомерном массивом с обьектами');
                    }

                    foreach ($value as $permission) {

                        if (!array_key_exists('menuItemId', $permission)) {
                            return $fail('menuItemId  обязательное поле в массиве c эллементами корзины');
                        }
                    }
                },
            ],
            ' * '                    => function ($attribute, $value, $fail) use ($allowFields) {

                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимый параметр, выберите одно из слудющих:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
