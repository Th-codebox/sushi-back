<?php


namespace App\Libraries\Payment\Contracts\Exceptions;


use App\Models\Order\Order;

class CancelOrderFailed extends \Exception
{
    public function __construct($message = "", Order $order)
    {
        $message = "Ошибка отмены заказа ID {$order->id}: {$message}";
        parent::__construct($message);
    }
}
