<?php

namespace App\Enums;

use App\Libraries\Traits\InlineLocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Web() - веб
 * @method static static Crm() - црм панель
 * @method static static DeliveryClub() - деливери клаб
 * @method static static Yandex() - яндекекс еда
 */
final class BasketSource extends Enum
{
    use InlineLocalizedEnum;

    const Web = 'web';
    const Crm = 'crm';
    const Yandex = 'yandex';
    const DeliveryClub = 'deliveryСlub';

    protected static array $lang = [
        self::Web => "Сайт",
        self::Crm => "CRM",
        self::Yandex => "Яндекс.Еда",
        self::DeliveryClub => "Delivery Club"
    ];
}
