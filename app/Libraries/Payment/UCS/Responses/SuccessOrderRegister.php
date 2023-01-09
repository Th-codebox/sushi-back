<?php


namespace App\Libraries\Payment\UCS\Responses;


use App\Libraries\Payment\Contracts\Exceptions\RegisterOrderFailed;
use App\Libraries\Payment\Contracts\OrderRegisterResponse;

class SuccessOrderRegister implements OrderRegisterResponse
{
    private string $redirectUrl;

    /**
     * SuccessOrderRegister constructor.
     * @throws RegisterOrderFailed
     */
    public function __construct($info)
    {
        if (empty($info->redirect_url) || empty($info->session)) {
            throw new RegisterOrderFailed("некоректные данные в ответе");
        }
        $this->redirectUrl = "{$info->redirect_url}?session={$info->session}";
    }

    /**
     * URL платежной страницы на которую нужно отправить пользователя
     * @return string
     */
    public function getPaymentUrl(): string
    {
        return $this->redirectUrl;
    }

}
