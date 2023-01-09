<?php


namespace App\Notifications\Contracts;


use App\Notifications\Contracts\SmsMessage;

interface SmsNotification
{
    /**
     * @param  mixed  $notifiable
     * @return \App\Notifications\Contracts\SmsMessage SmsMessage
     */
    public function toSms($notifiable) : SmsMessage;
}
