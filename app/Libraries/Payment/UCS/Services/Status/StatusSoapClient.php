<?php


namespace App\Libraries\Payment\UCS\Services\Status;


use App\Libraries\Payment\UCS\Entities\OrderId;
use App\Libraries\Payment\UCS\Services\AbstractSoapClient;
use SoapParam;


class StatusSoapClient extends AbstractSoapClient
{
    protected static array $classMap = [
        'OrderID' => OrderId::class,
        'get_by_orderResponse' => 'get_by_orderResponse',
        'Payment' => 'Payment'
    ];

    /**
     * Service Call: get_by_order
     * @param GetByOrderRequest $request
     * @return GetByOrderResponse
     */
    public function getByOrder(GetByOrderRequest $request)
    {
        $args = array();
        foreach ($request as $key => $val) {
            array_push($args, new SoapParam($val, $key));
        }

        return $this->__soapCall("get_by_order", $args);
    }
}


