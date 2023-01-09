<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use App\Libraries\Notification\SendNotification as SendNotification;

class NotificationSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ?string    $notificationType;
    private array  $to;
    private string $text;

    /**
     * NotificationSender constructor.
     * @param array $to
     * @param string $text
     * @param string|null $notificationHandler
     */
    public function __construct(array $to, string $text,?string $notificationHandler = null)
    {
        $this->notificationType = $notificationHandler;
        $this->to = $to;
        $this->text = $text;

        $this->queue = 'notification';
    }

    /**
     * @throws \App\Libraries\Notification\NotificationException
     */
    public function handle()
    {

       $send =  new SendNotification();
       $send->handle($this->to,$this->text,$this->notificationType);
       $send->sendNotification();
    }
}
