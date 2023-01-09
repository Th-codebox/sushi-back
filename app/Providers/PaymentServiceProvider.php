<?php

namespace App\Providers;

use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Libraries\Payment\UCS\UcsPaymentGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentGateway::class, function ($app) {
            return $app->make(UcsPaymentGateway::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
