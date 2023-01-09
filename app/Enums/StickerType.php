<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Child()
 * @method static static Spicy()
 * @method static static Vegetarian()
 */
final class StickerType extends Enum
{
    const Child = 'child';
    const Spicy = 'spicy';
    const Vegetarian = 'vegetarian';
}
