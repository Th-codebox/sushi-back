<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'ru'),
    'defaultCurrency' => config('app.currency', 'RUB'),
    'currencies' => [
        'iso' => 'all',
        'bitcoin' => 'all',
        'custom' => [
            // 'MY1' => 2,
            // 'MY2' => 3
        ]
    ]
];
