<?php


namespace App\Libraries\Payment\Contracts;



use App\Libraries\Payment\Contracts\Exceptions\OrderStatusException;
use App\Models\Order\Order;

use App\Libraries\Payment\Contracts\Exceptions\RegisterOrderFailed;

interface PaymentGateway
{

    /**
     * Регистрация платежа в платежной системе
     *
     * @param Order $shopOrder
     * @param string $returnUrlOk перенаправлять в случае удачной оплаты
     * @param string $returnUrlFail перенаправлять в случае ошибки
     *
     * @return OrderRegisterResponse
     *
     * @throws RegisterOrderFailed
     */
    public function registerOrder(Order $shopOrder,
                                  string $returnUrlOk,
                                  string $returnUrlFail
    ): OrderRegisterResponse;


    /**
     * Проверка статуса платежа
     *
     * @param Order $shopOrder
     * @return OrderStatusResponse
     *
     * @throws OrderStatusException
     */
    public function getStatus(Order $shopOrder): OrderStatusResponse;

    public function confirmOrder(Order $shopOrder);

    public function cancelOrder(Order $shopOrder);
}
