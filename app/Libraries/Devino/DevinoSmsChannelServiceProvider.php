<?php


namespace App\Libraries\Devino;


use App\Libraries\Devino\Api\SmsApiClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class DevinoSmsChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $apiKey = config('services.devino.key');

        Notification::resolved(function (ChannelManager $service) use ($apiKey) {
            $service->extend('sms', function ($app) use ($apiKey) {
                $apiClient = new SmsApiClient($apiKey);
                return new DevinoSmsChannel($apiClient);
            });

        });
    }
}
