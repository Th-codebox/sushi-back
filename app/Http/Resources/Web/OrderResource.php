<?php

namespace App\Http\Resources\Web;


use App\Libraries\Helpers\MoneyHelper;
use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class OrderResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var Order $item
         */

        return [
            'id'                              => (int)$item->id,
            'clientId'                        => (int)$item->client_id,
            'fullName'                        => (int)$item->getFullBasketItemsName(),
            'code'                            => (string)$item->code,
            'courierCell'                     => strlen($item->courier_cell) < 2 ? '0' . $item->courier_cell : $item->courier_cell,
            'deliveryPrice'                   => MoneyHelper::format($item->delivery_price),
            'deliveryType'                    => (string)$item->delivery_type,
            'totalPrice'                      => MoneyHelper::format($item->total_price),
            'discountAmount'                  => MoneyHelper::format($item->discount_amount),
            'paymentType'                     => (string)$item->payment_type,
            'orderStatus'                     => (string)$item->order_status,
            'filial'                          => (new FilialResource($this->whenLoaded('filial'))),
            'basket'                          => (new BasketResource($this->whenLoaded('basket'))),
            'cookingAndDeliveryTimeInMinutes' => $item->basket->timeToCompleteOrder() / 60,
            'deadLine'                        => $item->dead_line ? $item->dead_line->unix() : null,
            'createdAt'                       => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'payment'                         => (new PaymentResource($this->whenLoaded('payment'))),
        ];
    }
}
