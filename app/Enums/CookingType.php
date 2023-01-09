<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Cold()
 * @method static static Fried()
 * @method static static Baked()
 * @method static static Boiled()
 */
final class CookingType extends Enum
{
   public const Cold = 'cold';
   public const Fried = 'fried';
   public const Baked = 'baked';
   public const Boiled = 'boiled';
}
