<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Cash()
 * @method static static Terminal()
 */
final class CourierOrderPaymentType extends Enum
{
    const Cash = 'cash';
    const Terminal = 'terminal';
    const Paid = 'paid';
}
