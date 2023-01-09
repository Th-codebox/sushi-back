<?php

use App\Http\Controllers\Common\UploadController;
use App\Http\Controllers\Courier\Account\InformationController;
use App\Http\Controllers\Courier\Order\MoneyController as MoneyController;
use App\Http\Controllers\Courier\Order\OrderController as OrderController;
use Illuminate\Support\Facades\Route;


Route::group([
    'as'        => 'system.',
    'namespace' => 'System',
    'prefix'    => 'auth',
], function () {
    /**
     * Authorization routes
     */
    Route::post('login', [\App\Http\Controllers\Courier\System\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\Courier\System\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Courier\System\AuthController::class, 'refresh'])->name('refresh');
 //   Route::post('me', ['as' => 'me', 'uses' => 'AuthController@me']);
});

Route::group([
    'as'         => 'courier.',
    'middleware' => ['auth:crm','courier.check'],
], function () {

    Route::group([
        'as' => 'orders.',
    ], function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders');
        Route::get('orders/my', [OrderController::class, 'myOrder'])->name('my');
        Route::get('orders/history', [OrderController::class, 'history'])->name('history');
        Route::post('orders/{id}/start', [OrderController::class, 'orderStart'])->name('start');
        Route::post('orders/{id}/finish', [OrderController::class, 'orderFinish'])->name('finish');
        Route::post('orders/{id}/change_payment', [OrderController::class, 'changePayment'])->name('change_payment');
        Route::post('orders/confirm_cancelling', [OrderController::class, 'confirmCancelling'])->name('confirm_cancelling');
    });

    Route::group([
        'as'     => 'money.',
        'prefix' => 'money',
    ], function () {

        Route::get('balance', [MoneyController::class, 'getBalance'])->name('getBalance');

        Route::get('transfer', [MoneyController::class, 'transactions'])->name('transactions');
        Route::post('transfer/start', [MoneyController::class, 'transactionStart'])->name('transferStart');
        Route::get('transfer/{id}', [MoneyController::class, 'getTransaction'])->name('getTransfer');
        Route::post('transfer/{id}/confirm', [MoneyController::class, 'transactionConfirm'])->name('transferConfirm');
        Route::post('transfer/{id}/cancel', [MoneyController::class, 'transactionCancel'])->name('transferCancel');
        Route::put('transfer/{id}', [MoneyController::class, 'transactionChange'])->name('transferChange');
        Route::post('transfer/{id}/courierConfirm', [MoneyController::class, 'courierConfirm'])->name('courierConfirm');
        Route::post('transfer/{id}/managerConfirm', [MoneyController::class, 'managerConfirm'])->name('managerConfirm');
    });


    Route::group([
        'as' => 'information.',
    ], function () {
        Route::get('timetable', [InformationController::class, 'getTimeTable'])->name('timetable');
        Route::get('profile', [InformationController::class, 'getProfile'])->name('profile');
        Route::post('saveCord', [InformationController::class, 'saveCord'])->name('saveCord');
    });


});

