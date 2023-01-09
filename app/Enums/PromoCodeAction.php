<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DishGift() - блюдо в подарок
 * @method static static FriendPercent() - скидка за друга
 * @method static static Doubling() - удвоение заказа
 * @method static static BirthDay() - день рождения
 * @method static static Subtract() - скидка в у.е.
 * @method static static Percent()  - скидка в процентах
 */
final class PromoCodeAction extends Enum
{
    public const DishGift = 'dishGift';
    public const Doubling = 'doubling';
    public const BirthDay = 'birthDay';
    public const FriendPercent = 'friendPercent';
    public const Subtract = 'subtract';
    public const Percent = 'percent';
}
