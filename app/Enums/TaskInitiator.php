<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskInitiator extends Enum
{
    public const Default = 'default';
    public const BackgroundTask = 'background_task';
}
