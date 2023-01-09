<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Bundle()
 * @method static static Single()
 */
final class MenuItemType extends Enum
{
   public const Bundle = 'bundle';
   public const Single = 'single';
}
