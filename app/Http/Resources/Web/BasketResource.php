<?php

namespace App\Http\Resources\Web;

use App\Enums\OrderStatus;
use App\Libraries\BasketUpsellingItems;
use App\Models\Order\Basket;

use App\Services\CRM\Order\OrderService;
use App\Libraries\Helpers\MoneyHelper;


use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class BasketResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var Basket $item
         */


        $item->groupItems();

        $upSelling = new BasketUpsellingItems($item);

        $showButtonNoCall = true;
        return [
            'id'                     => (int)$item->id,
            'discountAmount'         => MoneyHelper::format($item->discount_amount),
            'deliveryPrice'          => MoneyHelper::format($item->delivery_price),
            'freeDelivery'           => MoneyHelper::format($item->free_delivery),
            'deliveryType'           => (string)$item->delivery_type,
            'totalPrice'             => MoneyHelper::format($item->total_price),
            'paymentType'            => (string)$item->payment_type,
            'dateDelivery'           => (string)$item->date_delivery,
            'timeDelivery'           => (string)$item->time_delivery,
            'clientMoney'            => MoneyHelper::format($item->client_money),
            'toDatetime'             => (boolean)$item->to_datetime,
            'persons'                => (int)$item->persons,
            'comment'                => (string)$item->comment,
            'commentForCourier'      => (string)$item->comment_for_courier,
            'clientAddress'          => (new ClientAddressResource($this->whenLoaded('clientAddress'))),
            'filial'                 => (new FilialResource($this->whenLoaded('filial'))),
            'items'                  => BasketItemGroupResource::collection($item->groupItems),
            'noCall'                 => $item->no_call,
            'showButtonNoCall'       => $showButtonNoCall,
            'needPersonsCount'       => $item->needPersonsCount ?? false,
            'promoCode'              => (new ClientPromoCodeResource($this->whenLoaded('clientPromoCode'))),
            'cookingAndDeliveryTime' => $item->timeToCompleteOrder(),
            'upSellingItems'         => MenuItemResource::collection($upSelling->getUpSellingItems()),

        ];
    }
}
