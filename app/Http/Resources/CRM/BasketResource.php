<?php

namespace App\Http\Resources\CRM;

use App\Libraries\BasketUpsellingItems;
use App\Models\Order\Basket;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Libraries\Helpers\MoneyHelper;

;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class BasketResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var Basket $item
         */

        $addressBreakComment = $item->client_address && $item->client_address->break_address ? ' АДРЕС КЛИЕНТА НЕ РАСПОЗНАН, ТРЕБУЕТСЯ УТОЧНЕНИЕ' : '';

        return [
            'id'                     => (int)$item->id,
            'filialId'               => (int)$item->filial_id,
            'deliveryPrice'          => MoneyHelper::format($item->delivery_price),
            'freeDelivery'           => MoneyHelper::format($item->free_delivery),
            'deliveryType'           => (string)$item->delivery_type,
            'totalPrice'             => MoneyHelper::format($item->total_price),
            'paymentType'            => (string)$item->payment_type,
            'basketSource'           => (string)$item->basket_source,
            'clientSource'           => (string)$item->client_source,
            'dateDelivery'           => $item->date_delivery ? $item->date_delivery->toDateTimeString() : null,
            'timeDelivery'           => (string)$item->time_delivery,
            'commentForCourier'      => (string)$item->comment_for_courier . $addressBreakComment,
            'paymentPhone'           => (string)$item->payment_phone,
            'clientMoney'            => MoneyHelper::format($item->client_money),
            'persons'                => (int)$item->persons,
            'comment'                => (string)$item->comment . $addressBreakComment,
            'address'                => (string)$item->address,
            'noCall'                 => (boolean)$item->no_call,
            'toDatetime'             => (boolean)$item->to_datetime,
            'clientAddress'          => (new ClientAddressResource($this->whenLoaded('clientAddress'))),
            'client'                 => (new ClientResource($this->whenLoaded('client'))),
            'items'                  => BasketItemResource::collection($this->whenLoaded('items')),
            'filial'                 => (new FilialResource($this->whenLoaded('filial'))),
            'createdAt'              => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'              => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'promoCode'              => (new ClientPromoCodeResource($this->whenLoaded('clientPromoCode'))),
            'cookingAndDeliveryTime' => $item->timeToCompleteOrder(),

        ];
    }
}
