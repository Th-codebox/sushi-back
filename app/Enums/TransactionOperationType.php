<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Send()
 * @method static static Receive()
 * @method static static TopUpOrder()
 * @method static static Bill()
 */
final class TransactionOperationType extends Enum
{
   public const Send = 'send';
   public const Receive = 'receive';
   public const TopUpOrder  = 'topUpOrder';
}

