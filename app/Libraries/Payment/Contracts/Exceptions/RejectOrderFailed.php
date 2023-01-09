<?php


namespace App\Libraries\Payment\Contracts\Exceptions;


use Throwable;

class RejectOrderFailed extends \Exception
{
    public function __construct($message = "", $gatewayErrorCode = "")
    {
        $message = "Ошибка платежного шлюза: {$message}, {$gatewayErrorCode}";
        parent::__construct($message);
    }
}
