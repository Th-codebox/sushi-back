<?php

use App\Enums\CheckType;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\VatType;

return [

    'default' => [
        'timezone' => 'Europe/Moscow',
        'telestore' => [
            'token' => env('TELESTORE_TOKEN')
        ],
        'atol' => [
            'prod_mode' => (bool) env('ATOL_PROD_MODE', false),

            'login' => env('ATOL_LOGIN'),
            'password' => env('ATOL_PASSWORD'),
            'group_code' => 'group_code_24385',

            'inn' => '4703178161',
            'sno' => SnoSystem::USN_INCOME_OUTCOME, // Упрощенная СН (доходы минус расходы)
            'vat' => VatType::NONE, // без НДС
            'payment_address' => 'https://www.sushifox.ru', // Адрес сайта

            'test' => [
                'login' => 'v4-online-atol-ru',
                'password' => 'iGFFuihss',
                'group_code' => 'v4-online-atol-ru_4179',

                'inn' => 5544332219,
                'sno' => SnoSystem::USN_INCOME_OUTCOME, // Упрощенная СН (доходы минус расходы)
                'vat' => VatType::NONE, // без НДС
                'payment_address' => 'https://v4.online.atol.ru', // Адрес сайта
            ]
        ],

        'ucs_payment_gateway' => [
            'prod_mode' => (bool) env('UCS_PAYMENT_PROD_MODE', false),
            'shop_id' => env('UCS_PAYMENT_SHOP_ID'),
            'login' => env('UCS_PAYMENT_LOGIN'),
            'password' => env('UCS_PAYMENT_PASSWORD'),

            'test' => [
                'login' => 'sushifox',
                'password' => 'VrDpUTC3uc',
                'shop_id' => 30021,
            ]
        ]

    ],

    /* Переназначение для конретного филиала */
    'custom' => [
        1 => [
            'atol' => [
                //'inn' => 123,
            ],

            'kkm_server' => [
                'host' => 'http://95.161.152.246:5893',
                'user' => 'Admin',
                'password' => '8801',
                'printers' => [
                    CheckType::Main => 1,
                    CheckType::Cold => 4,
                    CheckType::Hot => 5,
                    'sticker_cold' => 2,
                    'sticker_hot' => 3,
                ]
            ],

        ],
        2 => [
            'kkm_server' => [
                'host' => 'http://85.114.29.250:5893',
                'user' => 'Admin',
                'password' => '8801',
                'printers' => [
                    CheckType::Main => 1,
                    CheckType::Cold => 4,
                    CheckType::Hot => 5,
                    'sticker_cold' => 3,
                    'sticker_hot' => 2,
                ]
            ],

        ],
    ]
];
