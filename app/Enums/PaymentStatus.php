<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Wait()
 * @method static static Success()
 * @method static static Cancel()
 */
final class PaymentStatus extends Enum
{
   public const Wait = 'wait';
   public const Success = 'success';
   public const Cancel = 'cancel';
   public const WaitCancel = 'wait-cancel';
   public const Refund = 'refund';
}
