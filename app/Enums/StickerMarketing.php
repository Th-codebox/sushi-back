<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static New()
 * @method static static Hit()
 * @method static static Percent()
 * @method static static PersonalDiscount()
 */
final class StickerMarketing extends Enum
{
    const New = 'new';
    const Hit = 'hit';
    const Percent    = 'percent';
    const PersonalDiscount = 'personalDiscount';
}
