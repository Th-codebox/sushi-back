<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CollectionTransit() // инкасация
 * @method static static CourierTransit()  // курьер
 */
final class TransactionTransitType extends Enum
{
   public const CollectionTransit = 'collection_transit';
   public const CourierTransit = 'courier_transit';
}

