<?php

namespace App\Http;

use App\Http\Middleware\CourierApiDebug;
use App\Http\Middleware\ForceAcceptJson;
use App\Http\Middleware\PreventRequestsDuringMaintenance;

use Fruitcake\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;


class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        //ForceAcceptJson::class,
        // \App\Http\Middleware\TrustHosts::class,

        \App\Http\Middleware\TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\ProfileJsonResponse::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        /*'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],*/

        /**
         * don't use
         */
        'api' => [
            'throttle:api',
            ForceAcceptJson::class,
            HandleCors::class,
            SubstituteBindings::class,
        ],



        'web' => [
            'throttle:api',
            ForceAcceptJson::class,
            HandleCors::class,
            SubstituteBindings::class,
        ],

        'crm' => [
            'throttle:api',
            ForceAcceptJson::class,
            HandleCors::class,
            SubstituteBindings::class,
        ],

        'courier' => [
        //    'throttle:api',
            ForceAcceptJson::class,
            HandleCors::class,
            SubstituteBindings::class,
            CourierApiDebug::class
        ],

        'webhooks' => [
            'throttle:webhooks',
            ForceAcceptJson::class,
            HandleCors::class,
            SubstituteBindings::class,
        ],
        'stat' => [
            'throttle:api',
            HandleCors::class,
            SubstituteBindings::class,
        ],


    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'permission' => \App\Http\Middleware\Permission::class,
        'courier.check' => \App\Http\Middleware\CourierCheck::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'profile.json' => \App\Http\Middleware\ProfileJsonResponse::class
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        ForceAcceptJson::class,
        HandleCors::class,
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
