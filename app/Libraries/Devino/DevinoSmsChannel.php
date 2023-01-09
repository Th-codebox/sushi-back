<?php


namespace App\Libraries\Devino;


use App\Libraries\Devino\Api\SmsApiClient;
use App\Notifications\Contracts\SmsNotification;

class DevinoSmsChannel
{
    private SmsApiClient $apiClient;

    public function __construct(SmsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  App\Notifications\Contracts\SmsNotification  $notification
     * @return void
     */
    public function send($notifiable, SmsNotification $notification)
    {
        if ($toPhone = $notifiable->routeNotificationFor('sms', $notification)) {

            $message = $notification->toSms($notifiable);
            $this->apiClient->send($toPhone, $message->render());
            
        } else {
            //throw new DevinoSmsException("Не удалось определить телефон адресата");
        }
    }
}
