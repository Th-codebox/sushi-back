<?php

namespace App\Listeners\Request;

use App\Events\Order\ChangeOrderStatus;
use App\Events\Request\CreateRequest;
use App\Libraries\System\FilialSettings;
use Illuminate\Support\Facades\Mail;

class Notification
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param ChangeOrderStatus $event
     */
    public function handle(CreateRequest $event)
    {
        $filialSetting = new FilialSettings();
        $emails = $filialSetting->getDefault('emailsForNotifications');

        if (is_array($emails)) {
            foreach ($emails as $email) {
                /**
                 * @TODO SEND EMAIL
                 */
                Mail::send('emails.request', ['request' => $event->request], function ($m) use ($email) {
                    $m->from(config('MAIL_FROM_NAME'), 'sushifox');

                    $m->to($email)->subject('Новая заявка!');
                });
            }
        }
    }
}
