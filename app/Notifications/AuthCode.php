<?php

namespace App\Notifications;

use App\Notifications\Contracts\SmsMessage;
use App\Models\System\Client;
use App\Notifications\Contracts\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthCode extends Notification implements SmsNotification /*, ShouldQueue*/
{
    use Queueable;

    private string $authCodeString;

    /**
     * Create a new notification instance.
     *
     * @param string $authCode
     * @return void
     */
    public function __construct($authCode)
    {
        $this->authCodeString = $authCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'sms'];

        if ($notifiable instanceof Client) {
            #TODO Проверить наличие активных устройств и отправлять через канал пуш уведомлений
        }

        //return ['sms'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'authCodeString' => $this->authCodeString,
            'phone' => $notifiable->routeNotificationFor('sms')
        ];
    }

    /**
     * @param mixed $notifiable
     * @return \App\Notifications\Contracts\SmsMessage
     */
    public function toSms($notifiable) : SmsMessage
    {
        return (new SmsMessage())
            ->content("Ваш код авторизации: " . $this->authCodeString);
    }
}
