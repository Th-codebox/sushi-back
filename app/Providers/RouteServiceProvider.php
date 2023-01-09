<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->registerParametersPatterns();

        $this->routes(function () {

            Route::prefix('swager')
                ->as('swager.')
                ->middleware('web')
                ->namespace($this->namespace . "\Swager")
                ->group(base_path('routes/swager.php'));


            Route::prefix('web')
                ->as('web.')
                ->middleware('web')
                ->namespace($this->namespace . "\Web")
                ->group(base_path('routes/web.php'));


            Route::prefix('crm')
                ->middleware('crm')
                ->namespace($this->namespace)
                ->group(base_path('routes/crm.php'));


            Route::prefix('courier')
                ->middleware('courier')
                ->namespace($this->namespace . "\Courier")
                ->group(base_path('routes/courier.php'));


            Route::prefix('webhooks')
                ->middleware('webhooks')
                ->as('webhooks.')
                ->namespace($this->namespace . "\Webhooks")
                ->group(base_path('routes/webhooks.php'));

            Route::prefix('stat')
                ->middleware('stat')
                ->as('stat.')
                ->namespace($this->namespace)
                ->group(base_path('routes/stat.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('webhooks', function (Request $request) {
            return Limit::perMinute(500)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     *  Register global params pattern
     */
    protected function registerParametersPatterns()
    {
        Route::pattern('id', '[0-9]+');
    }
}
