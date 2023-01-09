<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static inProcess()
 * @method static static inOrder()
 * @method static static isCompleted()
 */
final class BasketStatus extends Enum
{
   public const inProcess = 'in_process';
   public const inOrder = 'in_order';
   public const isCompleted = 'is_completed';
}
