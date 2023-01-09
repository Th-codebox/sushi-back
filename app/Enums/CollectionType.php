<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Mobile()
 * @method static static Web()
 * @method static static Crm()
 */
final class CollectionType extends Enum
{
   public const Mobile = 'mobile';
   public const Desktop = 'desktop';
   public const Crm = 'crm';
}
