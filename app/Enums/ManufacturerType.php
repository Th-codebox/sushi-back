<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Hot()
 * @method static static Cold()
 * @method static static Collector()
 */
final class ManufacturerType extends Enum
{
   public const Hot = 'hot';
   public const Cold = 'cold';
   public const Collector = 'collector';
}
