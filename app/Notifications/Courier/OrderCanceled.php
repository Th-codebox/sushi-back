<?php

namespace App\Notifications\Courier;

use App\Http\Resources\Courier\OrderResource;
use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class OrderCanceled extends Notification
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
        return [FcmChannel::class, 'database'];
    }

    public function fcmProject($notifiable, $message)
    {
        // $message is what is returned by `toFcm`
        return 'sushi-fox'; // name of the firebase project to use
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
            'order' => $this->order->toArray()
        ];
    }

    public function toFcm($notifiable)
    {
        $data = (new OrderResource($this->order))->toArray('');

        return FcmMessage::create()
            ->setData([
                'orderId' => (string)$data['id'],
                'orderNumber' => (string)$data['orderNumber'],
                'address' => (string)$data['address']
            ])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Внимание! Заказ отменен!')
                ->setBody("Заказ {$this->order->order_number} отменен.")
                //->setImage('http://example.com/url-to-image-here.png')
                )
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('order_canceled'))
                    ->setNotification(AndroidNotification::create()
                        //->setColor('#0A0A0A')
                        ->setChannelId("Fox Delivery")
                        ->setSound("order_cancelled.wav")
                    )
            );
                /*->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('order_canceled'))
                );*/
    }
}
