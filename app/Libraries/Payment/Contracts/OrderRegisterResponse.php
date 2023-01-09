<?php


namespace App\Libraries\Payment\Contracts;


interface OrderRegisterResponse
{
    /**
     * URL платежной страницы на которую нужно отправить пользователя
     * @return string
     */
    public function getPaymentUrl(): string;

}

