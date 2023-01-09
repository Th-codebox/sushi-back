<?php


namespace App\Libraries\Payment\UCS\Responses;


use BenSampo\Enum\Enum;

class Payment
{
    public $id;
    public string $status;

    public function __construct($payment)
    {
        foreach ($payment as $key => $value) {
            $this->$key = $value;
        }
    }
}
