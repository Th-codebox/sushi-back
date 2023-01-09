<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Preparing()
 * @method static static ReadyForIssue()
 * @method static static InDelivery()
 * @method static static Completed()
 * @method static static Canceled()
 */
final class CourierOrderStatus extends Enum
{
    const Preparing = 'preparing';
    const ReadyForIssue = 'readyForIssue';
    const InDelivery = 'inDelivery';
    const Completed = 'completed';
    const Canceled = 'canceled';
}
