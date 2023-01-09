<?php

namespace App\Libraries\TeleStore\Enums;

use BenSampo\Enum\Enum;


/**
 * invite - поступление вызова,
 * answer - ответ абонента,
 * hangup - завершение вызова
 *
 * @method static static Invite()
 * @method static static Answer()
 * @method static static Hangup()
 */
final class CallWebhookEvent extends Enum
{
    const Invite = 'invite';
    const Answer = 'answer';
    const Hangup = 'hangup';

}
