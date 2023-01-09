<?php


namespace App\Http\Requests\CRM\Basket;


use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class CreateBasket extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowFields = [
            'clientId',
            'promoCode',
            'persons',
            'comment',
            'commentForCourier',
            'ip',
            'noCall',
            'clientSource',
            'basketSource',
            'clientAddressId',
            'dateDelivery',
            'timeDelivery',
            'paymentType',
            'clientMoney',
            'filialId',
            'items',
            'toDatetime',
        ];
        return [
            'clientId'          => 'required|numeric|exists:clients,id,deleted_at,NULL',
            'promoCode'         => 'string',
            'persons'           => 'numeric',
            'comment'           => 'string',
            'commentForCourier' => 'string',
            'clientSource'      => 'string',
            'ip'                => 'string',
            'noCall'            => 'bool',
            'dateDelivery'      => 'date',
            'timeDelivery'      => 'string',
            'paymentType'       => 'enum_value:' . PaymentType::class,
            'deliveryType'      => 'enum_value:' . DeliveryType::class,
            'basketSource'      => 'enum_value:' . DeliveryType::class,
            'clientMoney'     => 'numeric',
            'filialId'          => 'numeric|exists:filials,id',
            'toDatetime'        => 'bool',
            'items'             => [
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
            ' * '               => function ($attribute, $value, $fail) use ($allowFields) {

                if (!in_array($attribute, $allowFields)) {
                    return $fail($attribute . ' - недопустимый параметр, выберите одно из слудющих:' . implode(',', $allowFields));
                }
            },
        ];
    }
}
