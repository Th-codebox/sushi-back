<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Main()
 * @method static static Cold()
 * @method static static Hot()
 */
final class CheckType extends Enum
{
    public const Main = 'main';
    public const Cold = 'cold';
    public const Hot = 'hot';
}
