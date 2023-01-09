<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Green()
 * @method static static Yellow()
 * @method static static Red()
 */
final class PolygonType extends Enum
{
   public const Green = 'green';
   public const Yellow = 'yellow';
   public const Red = 'red';
}
