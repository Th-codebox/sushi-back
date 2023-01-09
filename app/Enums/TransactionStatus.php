<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static New()
 * @method static static Wait()
 * @method static static Confirm()
 * @method static static Completed()
 * @method static static Cancel()
 */
final class TransactionStatus extends Enum
{

   public const New = 'new'; // создана курьером
   public const Wait = 'wait'; //заполнена менеджером и ожидает подтверждения курьером. На этом этапе транзакция показывается курьеру
   public const Confirm = 'confirm'; // подверждена курьером
   public const Completed = 'completed'; // завершена менеджером. Баланс курьера изменился
   public const Cancel = 'cancel'; // транзакция отменена

}
