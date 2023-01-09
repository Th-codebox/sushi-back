<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Vacancy()
 * @method static static Construct()
 * @method static static Cooperation()
 */
final class RequestType extends Enum
{
    public const Vacancy = 'vacancy';
    public const Contact = 'contact';
    public const Cooperation = 'cooperation';
    public const Review = 'review';
}
