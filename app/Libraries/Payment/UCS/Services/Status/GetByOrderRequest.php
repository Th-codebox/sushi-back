<?php


namespace App\Libraries\Payment\UCS\Services\Status;


use App\Libraries\Payment\UCS\Entities\OrderId;

class GetByOrderRequest
{
    public OrderId $order;
}
