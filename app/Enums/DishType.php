<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Roll()
 * @method static static Sushi()
 * @method static static Pizza()
 * @method static static Salad()
 * @method static static Soup()
 * @method static static Souse()
 * @method static static Drink()
 */
final class DishType extends Enum
{
    public const Roll = 'roll';
    public const Sushi = 'sushi';
    public const Pizza = 'pizza';
    public const Salad = 'salad';
    public const Soup = 'soup';
    public const Drink = 'drink';
    public const Souse = 'souse';
}
