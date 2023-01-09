<?php

namespace App\Enums;

use App\Libraries\Traits\InlineLocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Cash()
 * @method static static Terminal()
 * @method static static Online()
 */
final class PaymentType extends Enum
{
    use InlineLocalizedEnum;

    public const Cash = 'cash';
    public const Terminal = 'terminal';
    public const Online = 'online';

    protected static array $lang = [
        self::Cash => "Наличными при получении",
        self::Terminal => "Картой курьеру при получении",
        self::Online => "Онлайн-оплата"
    ];

}
