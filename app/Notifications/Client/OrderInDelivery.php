<?php

namespace App\Notifications\Client;

use App\Models\Order\Order;
use App\Notifications\Contracts\SmsMessage;
use App\Models\System\Client;
use App\Notifications\Contracts\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderInDelivery extends Notification implements SmsNotification /*, ShouldQueue*/
{
    use Queueable;

    private Order $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
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

        /*if ($notifiable instanceof Client) {
            #TODO Проверить наличие активных устройств и отправлять через канал пуш уведомлений
        }*/

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
            'orderId' => $this->order->id,
            'orderNumber' => $this->order->code,
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
            ->content("Ваш заказ №{$this->order->code} готов и передан курьеру. Ожидайте.");
    }
}
