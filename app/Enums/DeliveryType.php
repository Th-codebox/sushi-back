<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Self()
 * @method static static Delivery()
 * @method static static Aggregate()
 * @method static static Yandex()
 * @method static static DeliveryClub()
 */
final class DeliveryType extends Enum
{
   public const Self = 'self';
   public const Delivery = 'delivery';
   public const Yandex = 'yandex';
   public const DeliveryClub = 'deliveryClub';
}
