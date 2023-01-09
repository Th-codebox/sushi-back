<?php


namespace App\Libraries\Payment\Contracts\Exceptions;


use Throwable;

class OrderStatusException extends \Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $message = "Ошибка получения статуса платежа: " . $message;
        parent::__construct($message, $code, $previous);
    }
}
