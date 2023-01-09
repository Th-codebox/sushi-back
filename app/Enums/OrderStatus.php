<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use phpDocumentor\Reflection\Types\This;

/**
 * @method static static WaitPayment() - Ожидает оплаты
 * @method static static New() -- Новый заказ
 * @method static static Confirm() - Потверждён
 * @method static static Preparing() - Готовится
 * @method static static Assembly() - В сборке
 * @method static static ReadyForIssue() - Готов к выдаче
 * @method static static InDelivery() - Достовляется
 * @method static static Completed() - Завершён
 * @method static static Canceled() - Отменен
 */
final class OrderStatus extends Enum
{
    public const WaitPayment = 'waitPayment';
    public const New = 'new';
    public const Confirm = 'confirm';
    public const Preparing = 'preparing';
    public const Assembly = 'assembly';
    public const ReadyForIssue = 'readyForIssue';
    public const InDelivery = 'inDelivery';
    public const Completed = 'completed';
    public const Canceled = 'canceled';


    /**
     * @param string $status
     * @return string
     */
    public static function prevStatus(string $status): string
    {
        $allPublicProperty = [
            self::WaitPayment,
            self::New,
            self::Confirm,
            self::Preparing,
            self::Assembly,
            self::ReadyForIssue,
            self::InDelivery,
            self::Completed,
            self::Canceled,
        ];
        try {
            $status = $allPublicProperty[array_search($status, $allPublicProperty) - 1];
        } catch (\Throwable $e) {
            $status = self::New;
        }
        return $status;
    }

    /**
     * @param string $status
     * @return string
     */
    public static function nextStatus(string $status): string
    {
        $allPublicProperty = [
            self::WaitPayment,
            self::New,
            self::Confirm,
            self::Preparing,
            self::Assembly,
            self::ReadyForIssue,
            self::InDelivery,
            self::Completed,
        ];
        try {

            $i = 1;

            /*if (in_array( $status,[ self::New])) {
                $status = self::WaitPayment;
            }*/

            $status = $allPublicProperty[array_search($status, $allPublicProperty) + $i];
        } catch (\Throwable $e) {

        }
        return $status;
    }
}
