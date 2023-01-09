<?php

namespace App\Http\Resources\CRM;

use App\Libraries\Helpers\MoneyHelper;
use App\Models\Order\Basket;
use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\JsonResource;


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
            'id'                        => (int)$item->id,
            'filialId'                  => $item->basket->filial_id,
            'code'                      => (string)$item->code,
            'courierCell'               => strlen($item->courier_cell) < 2 ? '0' . $item->courier_cell : $item->courier_cell,
            'kitchenCell'               => strlen($item->kitchen_cell) < 2 ? '0' . $item->kitchen_cell : $item->kitchen_cell,
            'promoCode'                 => (string)$item->promo_code,
            'deliveryPrice'             => MoneyHelper::format($item->delivery_price),
            'deliveryType'              => (string)$item->delivery_type,
            'totalPrice'                => MoneyHelper::format($item->total_price),
            'discountAmount'            => MoneyHelper::format($item->discount_amount),
            'paymentType'               => (string)$item->payment_type,
            'orderStatus'               => (string)$item->order_status,
            'additionalInfo'            => (array)$item->additional_info,
            'canceledConfirmByCourier'  => (bool)$item->canceled_confirm_by_courier,
            'clientAddress'             => (new ClientAddressResource($this->whenLoaded('clientAddress'))),
            'filial'                    => (new FilialShortResource($this->whenLoaded('filial'))),
            'basket'                    => (new BasketResource($this->whenLoaded('basket'))),
            'client'                    => (new ClientResource($this->whenLoaded('client'))),
            'courier'                   => (new UserResource($this->whenLoaded('courier'))),
            'manager'                   => (new UserResource($this->whenLoaded('manager'))),
            'createdAt'                 => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'                 => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'deadLine'                  => $item->dead_line ? $item->dead_line->unix() : null,
            'deadLineString'            => $item->dead_line ? $item->dead_line->format('Y-m-d H:i:s') : null,
            'cooking_and_delivery_time' => $item->cooking_and_delivery_time,
            'info'                      => $item->info(),
            'orderCompletedAt'          => $item->completedAt ? $item->completedAt->toDateTimeString() : null,
            'orderCanceledAt'           => $item->canceledAt ? $item->canceledAt->toDateTimeString() : null,
            'payment'                   => (new PaymentOrderResource($this->whenLoaded('payment'))),
        ];
    }
}
