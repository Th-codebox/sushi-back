<?php

namespace App\Notifications;

use App\Notifications\Contracts\SmsMessage;
use App\Models\System\Client;
use App\Notifications\Contracts\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentLink extends Notification implements SmsNotification /*, ShouldQueue*/
{
    use Queueable;

    private string $link;

    /**
     * Create a new notification instance.
     *
     * @param string $link
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
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
            'link' => $this->link
        ];
    }

    /**
     * @param mixed $notifiable
     * @return \App\Notifications\Contracts\SmsMessage
     */
    public function toSms($notifiable) : SmsMessage
    {
        return (new SmsMessage())
            ->content("Ваша ссылка для оплаты: " .  $this->link);
    }
}
