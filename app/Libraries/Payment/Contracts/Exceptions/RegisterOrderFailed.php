<?php


namespace App\Libraries\Payment\Contracts\Exceptions;


use Throwable;

class RegisterOrderFailed extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Ошибка платежного шлюза: " . $message;
        parent::__construct($message, $code, $previous);
    }
}
