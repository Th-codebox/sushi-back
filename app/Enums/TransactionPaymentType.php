<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Cash()
 * @method static static Terminal()
 */
final class TransactionPaymentType extends Enum
{
   public const Cash = 'cash';
   public const Terminal = 'terminal';
}
