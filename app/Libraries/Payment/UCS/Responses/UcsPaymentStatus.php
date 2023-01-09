<?php


namespace App\Libraries\Payment\UCS\Responses;


use App\Libraries\Payment\Contracts\Exceptions\OrderStatusException;
use App\Libraries\Payment\Contracts\OrderStatusResponse;
use BenSampo\Enum\Enum;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;

class UcsPaymentStatus extends Enum implements OrderStatusResponse
{
    const Registered = 'registered'; //заказ зарегистрирован и ожидает оплаты
    const InProgress = 'in_progress'; //начата обработка платежа
    const Authorized = 'authorized'; //успешная оплата, идёт оформление товара/услуги
    const Failed = 'failed';  //сбой в обработке заказа
    const Acknowledged = 'acknowledged'; //успешная оплата, оформление успешно завершено
    const NotAcknowledged = 'not_acknowledged'; //успешная оплата, оформление не выполнено
    const NotAuthorized = 'not_authorized'; //оплата заказа не удалась
    const Canceled = 'canceled'; //оформление не удалось, оплата отменена
    const Refunded = 'refunded'; //произведён возврат успешно оформленного заказа

    public $gatewayResponse;

    public ?Payment $payment = null;

    /**
     * @param mixed $response
     *
     * @throws OrderStatusException
     */
    public function __construct($response)
    {
        $this->gatewayResponse = $response;

        if (empty($response->status)) {
            throw new OrderStatusException("некоректные данные в ответе");
        }

        try {
            parent::__construct($response->status);
        } catch (InvalidEnumMemberException $e) {
            throw new OrderStatusException("неизвестный статус платежа ({$response->status})", 0, $e);
        }

        if (isset($response->payments->Payment)) {
            $this->payment = new Payment($response->payments->Payment);
        }

    }

    public function getStatus()
    {
        return $this->value;
    }

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::Registered: return 'заказ зарегистрирован и ожидает оплаты';
            case self::InProgress: return 'начата обработка платежа';
            case self::Authorized: return 'успешная оплата, идёт оформление товара/услуги';
            case self::Failed: return 'сбой в обработке заказа';
            case self::Acknowledged: return 'успешная оплата, оформление успешно завершено';
            case self::NotAcknowledged: return 'успешная оплата, оформление не выполнено';
            case self::NotAuthorized: return 'оплата заказа не удалась';
            case self::Canceled: return 'оформление не удалось, оплата отменена';
            case self::Refunded: return 'произведён возврат успешно оформленного заказа';
        }

        return parent::getDescription($value);
    }


    /*
   * Одно- (1) или двух-(2) стадийная оплата
   */
    public function isTwoStep(): bool
    {
        return false;
    }

    /**
     * (1) - не используется
     * (2) - Деньги зарезервированы
     */
    public function isAuthorized(): bool
    {
        //успешная оплата, оформление не выполнено
        return $this->value == self::NotAcknowledged;
    }

    /*
     * (1) - оплачено
     * (2) - деньги списаны
     */
    public function isCompleted(): bool
    {
        return $this->in([
            self::Acknowledged,
            self::NotAcknowledged,
            self::Authorized
        ]);
    }

    /*
     * (1) - оплата отменена или не успешна
     * (2) - деньги не списаны (возвращены)
     */
    public function isCancelled(): bool
    {
        return $this->in([
            self::Canceled,
            self::Refunded,
            self::NotAuthorized,
            self::Failed
        ]);
    }
}
