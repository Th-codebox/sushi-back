<?php


namespace App\Libraries;


use App\Libraries\Notification\NotificationException;
use App\Libraries\Notification\SendersInterface;
use Illuminate\Support\Facades\Mail;

/**
 * Class SmsSender
 * @package App\Library
 */
class EmailSender implements SendersInterface
{

    private ?string $sender;


    public function __construct()
    {
        $this->sender = config('app.mail_from_address');
    }

    /**
     * @param array $to
     * @param string $text
     * @return bool|mixed
     * @throws NotificationException
     */
    public function send(array $to, string $text)
    {
        try {

            $to = is_array($to) ? $to : [$to];

            Mail::send('emails.simple_notification_text', ['text' => $text], function ($message) use ($to) {
                $message->from([$this->sender => $this->sender]);
                $message->to($to);
            });

            return true;

        } catch (\Throwable $e) {
            throw new NotificationException($e->getMessage());
        }

    }
}
