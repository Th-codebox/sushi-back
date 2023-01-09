<?php


namespace App\Libraries\Payment\Contracts;


interface OrderStatusResponse
{
    public function getStatus();

    /*
     * Одно- (1) или двух-(2) стадийная оплата
     */
    public function isTwoStep(): bool;

    /**
     * (1) - не используется
     * (2) - Деньги зарезервированы
     */
    public function isAuthorized(): bool;

    /*
     * (1) - оплачено
     * (2) - деньги списаны
     */
    public function isCompleted(): bool;

    /*
     * (1) - не используется
     * (2) - деньги не списаны (возвращены)
     */
    public function isCancelled(): bool;
}
