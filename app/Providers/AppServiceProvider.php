<?php

namespace App\Providers;

use App\Models\System\Transaction;
use App\Models\Order\Basket;
use App\Models\Order\BasketItem;
use App\Models\Order\Order;
use App\Models\System\Activity;
use App\Models\System\Client;
use App\Observers\Courier\TransactionObserver;
use App\Observers\Order\BasketItemObserver;
use App\Observers\Order\BasketObserver;
use App\Observers\Order\OrderObserver;
use App\Observers\System\ActivityObserver;
use App\Observers\System\ClientObserver;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            //$this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Transaction::observe(TransactionObserver::class);
        Order::observe(OrderObserver::class);
        Basket::observe(BasketObserver::class);
        Client::observe(ClientObserver::class);
        BasketItem::observe(BasketItemObserver::class);
        Activity::observe(ActivityObserver::class);
        //   DB::listen(function ($query) {
        //       var_dump($query->sql);
        //   });
    }
}
