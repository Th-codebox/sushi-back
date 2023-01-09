<?php

namespace App\Libraries\TeleStore\Enums;

use BenSampo\Enum\Enum;


/**
 * number - городской номер,
 * neighbour - "свой" номер,
 * account - внутренний аккаунт
 *
 * @method static static Number()
 * @method static static Neighbour()
 * @method static static Account()
 */
final class CallWebhookSource extends Enum
{
    const Number = 'number';
    const Neighbour = 'neighbour';
    const Account = 'account';

}
