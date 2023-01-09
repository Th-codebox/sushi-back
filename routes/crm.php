<?php

use App\Http\Controllers\Common\CallBackController;
use App\Http\Controllers\Common\DaDataController;
use App\Http\Controllers\Common\UploadController;
use App\Http\Controllers\CRM\Order\BasketController;
use App\Http\Controllers\CRM\Order\OrderController;
use App\Http\Controllers\CRM\System\PrintingController;
use Illuminate\Support\Facades\Route;


Route::group([
    'as'         => 'crm.',
    'middleware' => 'api',
    'namespace'  => 'CRM',
], function ($router) {

    Route::group([
        'as'        => 'system.',
        'namespace' => 'System',
        'prefix'    => 'auth',
    ], function () {
        /**
         * Authorization routes
         */
        Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
        Route::post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
        Route::post('refresh', ['as' => 'refresh', 'uses' => 'AuthController@refresh']);
        Route::post('me', ['as' => 'me', 'uses' => 'AuthController@me']);
    });

    Route::group([
        'as'         => 'administrator.',
        'prefix'     => 'administrator',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::get('getShortInfo', [\App\Http\Controllers\CRM\System\FilialCashBoxController::class, 'getShortInfo'])->name('getShortInfo');
        Route::get('getBalance', [\App\Http\Controllers\CRM\System\FilialCashBoxController::class, 'getBalanceAndInformationByOpenCashBox'])->name('getBalanceAndInformationByOpenCashBox');
        Route::post('openWorkDay', [\App\Http\Controllers\CRM\System\FilialCashBoxController::class, 'openCashBox'])->name('openCashBox');
        Route::post('closeWorkDay', [\App\Http\Controllers\CRM\System\FilialCashBoxController::class, 'closeCashBox'])->name('closeCashBox');

    });


    Route::group([
        'as'         => 'common.',
        'prefix'     => 'common',
        'middleware' => ['auth:crm'],
    ], function () {
        Route::get('checkAddressIsDeliveryByString', [DaDataController::class, 'checkAddressIsDeliveryByString'])->name('checkAddressIsDeliveryByString');
        Route::post('uploadImage', [UploadController::class, 'uploadImage'])->name('uploadImage');
        Route::post('uploadDoc', [UploadController::class, 'uploadDoc'])->name('uploadDoc');
        Route::get('getSuggestName', [DaDataController::class, 'getSuggestName'])->name('getSuggestName');
        Route::get('getSuggestAddress', [DaDataController::class, 'getSuggestAddress'])->name('getSuggestAddress');

        Route::post('callback', [CallBackController::class, 'callback'])->name('callback');
    });

    Route::group([
        'as'         => 'user.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('users', 'UserController');

    });

    Route::group([
        'as'         => 'role.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('roles', 'RoleController');
    });
    Route::group([
        'as'         => 'promoCodes.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('promoCodes', 'PromoCodeController');
    });

    Route::group([
        'as'         => 'promotions.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('promotions', 'PromotionController');
    });

    Route::group([
        'as'         => 'news.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('news', 'NewsController');
    });

    Route::group([
        'as'         => 'slider.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('sliders', 'SliderController');
    });

    Route::group([
        'prefix'         => 'tablet',
        'as'         => 'cookingSchedule.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm'],
    ], function () {
        Route::get('getHots', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'getHots'])->name('getHots');
        Route::post('completeHot/{id}', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'completeHot'])->name('completeHot');
        Route::get('getColds', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'getColds'])->name('getColds');
        Route::post('completeCold/{id}', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'completeCold'])->name('completeCold');
        Route::get('getAssemblies', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'getAssembly'])->name('getAssembly');
        Route::post('completeAssembly/{id}', [\App\Http\Controllers\CRM\System\CookingScheduleController::class, 'completeAssembly'])->name('completeAssembly');
    });


    Route::group([
        'as'         => 'workSchedule.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('workSchedules', 'WorkScheduleController');
        Route::get('workSchedule/getCountStaffByDate', [\App\Http\Controllers\CRM\System\WorkScheduleController::class, 'getCountStaffByDate'])->name('getCountStaffByDate');
        Route::get('workSchedules/getScheme/{filialId}', [\App\Http\Controllers\CRM\System\WorkScheduleController::class, 'getScheme'])->name('getScheme');
        Route::post('workSchedules/setDay', [\App\Http\Controllers\CRM\System\WorkScheduleController::class, 'setDay'])->name('setDay');


    });

    Route::group([
        'as'         => 'permission.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::get('permissions', ['as' => 'permissions', 'uses' => 'PermissionController@index']);
    });

    Route::group([
        'as'         => 'filial.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('filials', 'FilialController');

    });

    Route::group([
        'as'         => 'crm.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('clients', 'ClientController');

        Route::put('clients/{id}/toBlackList', [\App\Http\Controllers\CRM\System\ClientController::class, 'addToBlackList'])->name('addToBlackList');
        Route::put('clients/{id}/addAddress', [\App\Http\Controllers\CRM\System\ClientController::class, 'addClientAddress'])->name('addClientAddress');
        Route::put('clients/{id}/removeFromBlackList', [\App\Http\Controllers\CRM\System\ClientController::class, 'removeFromBlackList'])->name('removeFromBlackList');
        Route::get('clients/{id}/getClientCrmActiveBasket', [BasketController::class, 'getClientCrmActiveBasket'])->name('getClientCrmActiveBasket');
    });

    Route::group([
        'as'         => 'crm.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('clientAddresses', 'ClientAddressController');
    });


    Route::group([
        'as'         => 'polygon.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('polygons', 'PolygonController');
    });

    Route::group([
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::post('printing/print-check', [PrintingController::class, 'printCheck']);
        Route::post('printing/print-sticker', [PrintingController::class, 'printSticker']);
    });


    Route::group([
        'as'         => 'order.',
        'namespace'  => 'Order',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('baskets', 'BasketController');
        /**
         * Эллементы корзины
         */
        Route::post('baskets/{id}/items', [BasketController::class, 'addBasketItem'])->name('basket.addBasketItem');
        Route::patch('baskets/items/{id}', [BasketController::class, 'updateBasketItem'])->name('basket.updateBasketItem');
        Route::delete('baskets/items/{id}', [BasketController::class, 'deleteItem'])->name('basket.delete');
        Route::delete('baskets/{id}/items', [BasketController::class, 'deleteItems'])->name('basket.deleteItems');
        Route::patch('baskets/{id}/changeCourier', [BasketController::class, 'changeCourier'])->name('basket.changeCourier');
        Route::patch('baskets/{id}/changeOnYandexDelivery', [BasketController::class, 'changeOnYandexDelivery'])->name('basket.changeOnYandexDelivery');
        Route::patch('baskets/{id}/changeOnDeliveryClub', [BasketController::class, 'changeOnDeliveryClub'])->name('basket.changeOnDeliveryClub');
        Route::patch('baskets/{id}/changePickup', [BasketController::class, 'changePickUp'])->name('basket.changePickUp');
        Route::patch('baskets/{id}/changePayment', [BasketController::class, 'changePaymentType'])->name('basket.changePayment');
        Route::patch('baskets/{id}/addPromoCode', [BasketController::class, 'addPromoCode'])->name('basket.addPromoCode');
    });
    Route::group([
        'as'         => 'order.',
        'namespace'  => 'Order',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('orders', 'OrderController');
        Route::post('orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('cancelOrder');
        Route::post('orders/{id}/confirm', [OrderController::class, 'confirmOrder'])->name('confirmOrder');
        Route::post('orders/{id}/sendLinkOnPayment', [OrderController::class, 'sendLinkOnPayment'])->name('sendLinkOnPayment');
        Route::patch('orders/{id}/changeOnNextStatus', [OrderController::class, 'changeOnNextStatus'])->name('orders.changeOnNextStatus');
        Route::get('orders/{id}/mainCheck', [OrderController::class, 'getMainCheck'])->name('orders.getMainCheck');
        Route::get('orders/{id}/coldCheck', [OrderController::class, 'getColdCheck'])->name('orders.getColdCheck');
        Route::get('orders/{id}/hotCheck', [OrderController::class, 'getHotCheck'])->name('orders.getHotCheck');
        Route::get('order/getStatuses', [OrderController::class, 'getStatuses'])->name('getStatuses');

        Route::get('orders/{id}/singleMenuItems', [OrderController::class, 'getSingleMenuItems'])->name('getSingleMenuItems');
    });

    Route::group([
        'as'        => 'order.',
        'namespace' => 'Order',
    ], function () {
        Route::get('orders/{id}/mainCheck', [OrderController::class, 'getMainCheck'])->name('orders.getMainCheck');
        Route::get('orders/{id}/coldCheck', [OrderController::class, 'getColdCheck'])->name('orders.getColdCheck');
        Route::get('orders/{id}/hotCheck', [OrderController::class, 'getHotCheck'])->name('orders.getHotCheck');
    });


    Route::group([
        'as'         => 'transactions.',
        'namespace'  => 'Courier',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('transactions', 'TransactionController');
        Route::patch('transactions/{id}/completed', [\App\Http\Controllers\CRM\Courier\TransactionController::class, 'completeTransaction'])->name('transactions.completeTransaction');
        Route::patch('transactions/{id}/canceled', [\App\Http\Controllers\CRM\Courier\TransactionController::class, 'cancelTransaction'])->name('transactions.cancelTransaction');
        Route::patch('transactions/{id}/wait', [\App\Http\Controllers\CRM\Courier\TransactionController::class, 'waitTransaction'])->name('transactions.waitTransaction');
    });


    Route::group([
        'as'         => 'category.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('categories', 'CategoryController');
    });


    Route::group([
        'as'         => 'modification.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('modifications', 'ModificationController');
    });

    Route::group([
        'as'         => 'collection.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('collections', 'CollectionController');
    });

    Route::group([
        'as'         => 'workSpaces.',
        'namespace'  => 'System',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('workSpaces', 'WorkSpaceController');
    });

    Route::group([
        'as'         => 'technicalCard.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('technicalCards', 'TechnicalCardController');
    });

    Route::group([
        'as'         => 'setting.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('settings', 'SettingController');
    });

    Route::group([
        'as'        => 'setting.',
        'namespace' => 'Store',
    ], function () {
        Route::get('getSetting', [\App\Http\Controllers\CRM\Store\SettingController::class, 'getSetting'])->name('getSetting');
    });

    Route::group([
        'as'         => 'menuItem.',
        'namespace'  => 'Store',
        'middleware' => ['auth:crm', 'permission'],
    ], function () {
        Route::resource('menuItems', 'MenuItemController');
        Route::post('menuItems/{id}/saveModifications', ['as' => 'saveModifications', 'uses' => 'MenuItemController@saveModifications']);
    });


});

