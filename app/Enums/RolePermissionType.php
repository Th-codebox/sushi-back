<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static schedules()
 * @method static static filials()
 * @method static static all()
 */
final class RolePermissionType extends Enum
{
    const Schedules = 'schedules';
    const Filials = 'filials';
    const All = 'all';
}
