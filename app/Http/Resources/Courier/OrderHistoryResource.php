<?php

namespace App\Http\Resources\Courier;

use App\Enums\CourierOrderStatus;
use App\Libraries\Helpers\MoneyHelper;
use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class OrderResource
 * @package App\Http\Resources\Courier
 * @property Order $resource
 */
class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $order = $this->resource;
        date_default_timezone_set('UTC');
        return [
            "id"                       => $order->id, // номер и айди заказа  (генерируется по нарастающей)
            "orderNumber"              => $order->code . '-' . $order->kitchen_cell,  //
            "date"                     => $order->date ? $order->date->unix() : Carbon::now()->unix(),  // дата заказа
            "price"                    => MoneyHelper::format($order->total_price),  // сумма/цена заказа
            "address"                  => $order->clientAddress ? $order->clientAddress->getFullAddress() : "",  // адрес заказа
            //   "latitude" => $order->latitude, //широта  адреса заказа
            //   "longitude" => $order->longitude, //долгота адреса заказа
            //   "comment" => $order->comment,  // коммент к заказу
            //    "clientName" => $order->client_name,  // имя клиента
            //   "phone" => $order->phone,  // номер телефона клиента
            "deadLine"                 => $order->dead_line ? $order->dead_line->unix() : Carbon::now()->unix(),  // время предполагаемой доставки
            "completed"                => (string)$order->order_status === CourierOrderStatus::Completed,
            "canceled"                 => (string)$order->order_status === CourierOrderStatus::Canceled,
            // оплата
            "paymentType"              => (string)$order->payment_type,  //Order.PaymentType,
            "deliveryTime"             => (int)$order->travelTime(), // мои заказы (завершить приступить)
            "isLate"                   => (bool)$order->hasLateness(), // мои заказы (завершить приступить)
            "diffTime"                 => (int)$order->diffTimeDelivery(), // мои заказы (завершить приступить)
            "canceledConfirmByCourier" => (bool)$order->canceled_confirm_by_courier, // мои заказы (завершить приступить)
            //      "readyForIssue" => (bool)$order->ready_for_delivery,  //в ожидании (взять в готовке)
        ];
    }
}
