<?php


namespace App\Libraries\Notification;


use App\Libraries\EmailSender;
use App\Libraries\SmsSender;


class SendNotification extends NotificationHandler
{

    private array $notificationTypes = [
        'sms'   => SmsSender::class,
        'email' => EmailSender::class,
    ];

    /**
     * @param array $to
     * @param string $text
     * @param string|null $notificationType
     * @return bool|void
     */
    public function handle(array $to, string $text, ?string $notificationType = null)
    {
        if ($notificationType === null) {
            $this->notificationType = config('app.notification_type', 'email');
        } else {
            $this->notificationType = $notificationType;
        }

        $this->to = $to;
        $this->text = $text;
    }

    /**
     * @return bool
     * @throws NotificationException
     */
    public function sendNotification(): bool
    {
        $senderStatus = false;

        if (array_key_exists($this->notificationType, $this->notificationTypes) && is_string($this->notificationTypes[$this->notificationType])) {
            try {
                /**
                 * @var SendersInterface $sender
                 */
                $sender = new $this->notificationTypes[$this->notificationType];
                $senderStatus = $sender->send($this->to, $this->text);
            } catch (\Throwable $e) {
                throw new NotificationException($e->getMessage());
            }
        }

        return $senderStatus;
    }
}
