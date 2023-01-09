<?php

namespace App\Http\Resources\Courier;

use App\Enums\OrderStatus;
use App\Models\Order\Order;
use App\Libraries\Helpers\MoneyHelper;

;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class OrderResource
 * @package App\Http\Resources\Courier
 * @property Order $resource
 */
class OrderResource extends JsonResource
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


        $status = null;

        if ($order->order_status->is(OrderStatus::ReadyForIssue)) {
            $status = 'readyForDelivery';
        } elseif ($order->order_status->is(OrderStatus::Assembly)) {
            $status = OrderStatus::Preparing;
        }
        //date_default_timezone_set('UTC');
        return [
            "id"          => $order->id, // номер и айди заказа  (генерируется по нарастающей)
            "orderNumber" => $order->code . '-' . $order->courier_cell,  //
            "date"        => $order->date ? $order->date->unix() : Carbon::now()->unix(),  // дата заказа
            "price"       => MoneyHelper::format($order->total_price),  // сумма/цена заказа
            "address"     => $order->clientAddress ? $order->clientAddress->getFullAddress() : "",  // адрес заказа
            "latitude"    => $order->clientAddress ? (double)$order->clientAddress->lat_geo : 0.00, //широта  адреса заказа
            "longitude"   => $order->clientAddress ? (double)$order->clientAddress->let_geo : 0.00, //долгота адреса заказа
            "comment"     => (string)$order->basket->comment_for_courier,  // коммент к заказу
            "clientName"  => (string)$order->client->name,  // имя клиента
            "phone"       => '+' .(string)$order->client->phone,  // номер телефона клиента
            "deadLine"    => $order->dead_line ? $order->dead_line->unix() : Carbon::now()->unix(),  // время предполагаемой доставки
            "startAt"     => $order->start_at ? $order->start_at->unix() : Carbon::now()->unix(),  // время предполагаемой доставки
            "status"      => $status ?: (string)$order->order_status,  //Status,
            "paymentType" => (string)$order->payment_type,  //Order.PaymentType,
            "clientMoney" => (string)MoneyHelper::format($order->client_money),  //клиент указал с какого номинала купюры ему необходима сдача
        ];
    }
}
