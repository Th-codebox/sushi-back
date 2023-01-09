<?php

namespace App\Libraries\Notification;

/**
 * Interface NotificationInterface
 * @package App\Library\Notification
 */
interface NotificationInterface
{
    /**
     * @param array $to
     * @param string $text
     * @param string|null $type
     * @return bool
     */
    public function handle(array $to, string $text,?string $type = null);
}
