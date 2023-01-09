<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static All()
 * @method static static Personal()
 */
final class PromoCodeType extends Enum
{
    public const All = 'all';
    public const Personal = 'personal';
}
