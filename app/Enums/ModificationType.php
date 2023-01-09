<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Select()
 * @method static static Single()
 */
final class ModificationType extends Enum
{
    const Select = 'select';
    const Single = 'single';
}
