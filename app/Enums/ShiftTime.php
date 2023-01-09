<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Full()
 * @method static static Second()
 * @method static static First()
 * @method static static OffDay()
 */
final class ShiftTime extends Enum
{
    public const Full = 'full'; // полная смена
    public const Second = 'second'; //вторая смена
    public const First = 'first'; // первая смена
    public const OffDay = 'offDay'; //выходной
}
