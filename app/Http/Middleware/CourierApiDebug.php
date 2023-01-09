<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourierApiDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::channel('couriers')->debug('Request', [
            'url' => $_SERVER['REQUEST_URI'],
            'user_id' => auth()->user() ? auth()->user()->id : "-",
            'request' => $request->all(),
        ]);
        return $next($request);
    }
}
