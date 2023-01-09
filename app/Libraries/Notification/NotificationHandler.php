<?php

namespace App\Libraries\Notification;


/**
 * Class NotificationHandler
 * @package App\Library\Notification
 */
abstract class NotificationHandler implements NotificationInterface
{
    protected string $notificationType;
    protected array $to;
    protected string $text;

    /**
     * @param array $to
     * @param string $text
     * @param string|null $type
     * @return bool
     */
    abstract public function handle(array $to, string $text,?string $type = null);
}
