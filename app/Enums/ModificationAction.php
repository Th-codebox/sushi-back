<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Subtract()
 * @method static static Add()
 * @method static static Replace()
 * @method static static Construct()
 */
final class ModificationAction extends Enum
{
    const Subtract = 'subtract';
    const Add = 'add';
    const Replace = 'replace';
    const Construct = 'construct';
}
