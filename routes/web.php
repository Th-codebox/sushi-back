<?php

use App\Http\Controllers\Common\DaDataController;
use App\Http\Controllers\Web\Action\ActionController;
use App\Http\Controllers\Web\Action\PaymentController;
use App\Http\Controllers\Web\Client\ClientController;
use App\Http\Controllers\Web\Information\NewsController;
use App\Http\Controllers\Web\Information\PolygonController;
use App\Http\Controllers\Web\Information\PromotionController;
use App\Http\Controllers\Web\Information\SliderController;
use App\Http\Controllers\Web\Menu\CategoryController;

use App\Http\Controllers\Web\Menu\MenuItemController;
use App\Http\Controllers\Web\Menu\CollectionController;
use App\Http\Controllers\Web\Order\BasketController;

use App\Http\Controllers\Web\Client\ClientAddressController;
use App\Http\Controllers\Web\Order\OrderController;
use Illuminate\Support\Facades\Route;


/****************************
 *  Доступно без авторизации
 ****************************/

/*
 * Каталог
 */
Route::group([
    'as'     => 'menu.',
    'prefix' => 'menu',
], function () {

    Route::get('upSellingItems', [MenuItemController::class, 'upSellingItems'])->name('upSellingItems');
    Route::get('items', [MenuItemController::class, 'index'])->name('menuItems.index');
    Route::get('items/{param}', [MenuItemController::class, 'show'])->name('menuItems.show');
    Route::get('collections/{type}/{target}', [CollectionController::class, 'getCollectionsByParam'])->name('colections.getCollectionsByParam');
    Route::get('categories/{param}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

});
/*
 * Каталог
 */
Route::group([
    'as'     => 'information.',
    'prefix' => 'information',
], function () {

    Route::get('sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('polygons', [PolygonController::class, 'index'])->name('polygons.index');
    Route::get('promotions', [PromotionController::class, 'index'])->name('polygons.index');
    Route::get('promotions/{param}', [PromotionController::class, 'show'])->name('polygons.show');
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{param}', [NewsController::class, 'show'])->name('news.show');
});

/*
 * Каталог
 */
Route::group([
    'as'     => 'mail.',
    'prefix' => 'mail',
], function () {

    Route::post('vacancy', [ActionController::class, 'sendVacancy'])->name('mail.sendVacancy');
    Route::post('feedback', [ActionController::class, 'sendFeedBack'])->name('mail.sendFeedBack');
    Route::post('cooperation', [ActionController::class, 'sendCooperation'])->name('mail.sendCooperation');
    Route::post('review', [ActionController::class, 'sendReview'])->name('mail.sendReview');

});

Route::get('common/checkAddressIsDeliveryByString', [DaDataController::class, 'checkAddressIsDeliveryByString'])->name('checkAddressIsDeliveryByString');
Route::get('common/getSuggestAddress', [DaDataController::class, 'getSuggestAddress'])->name('getSuggestAddress');
/*****************************
 *  Авторизация
 *****************************/
Route::group([
    'as'        => 'client.',
    'namespace' => 'Client',
    'prefix'    => 'auth',
], function () {
    /**
     * Authorization routes
     */
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::post('me', ['as' => 'me', 'uses' => 'AuthController@me']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    Route::post('sendCode', ['as' => 'sendCode', 'uses' => 'AuthController@sendCode']);
    Route::post('refresh', ['as' => 'refresh', 'uses' => 'AuthController@refresh']);
});


Route::group([
    'as'         => 'web.',
    'middleware' => ['auth:web'],
], function () {

    Route::group([
        'as'     => 'common.',
        'prefix' => 'common',
    ], function () {
        Route::post('uploadImage', [\App\Http\Controllers\Common\UploadController::class, 'uploadImage'])->name('uploadImage');
        ///     Route::get('getSuggestAddress', [DaDataController::class, 'getSuggestAddress'])->name('getSuggestAddress');


    });

    Route::group([
        'as'     => 'client.',
        'prefix' => 'client',
    ], function () {

        /**
         * Данные клиента
         */
        Route::get('profile', [ClientController::class, 'getProfile'])->name('getProfile');
        Route::patch('profile', [ClientController::class, 'editProfile'])->name('editProfile');

        Route::post('addPromoCode', [ClientController::class, 'addPromoCode'])->name('addPromoCode');

        Route::get('address', [ClientAddressController::class, 'getClientAddress'])->name('getClientAddress');
        Route::post('address', [ClientAddressController::class, 'addAddress'])->name('addAddress');


        Route::patch('address/{id}', [ClientAddressController::class, 'editAddress'])->name('editAddress');
        Route::delete('address/{id}', [ClientAddressController::class, 'deleteAddress'])->name('deleteAddress');

        Route::get('address/{id}/getPolygons', [ClientAddressController::class, 'getPolygonsHavingPointAddress'])->name('checkPolygon');
    });


});
/*****************************
 *  Доступно после авторизации
 *****************************/

Route::group([
    'as'         => 'basketWeb.',
], function () {
    /**
     * Эллементы корзины
     */
    Route::post('basket/item', [BasketController::class, 'addBasketItem'])->name('basket.addBasketItem');
    //   Route::patch('basket/item/{id}', [BasketController::class, 'updateBasketItem'])->name('basket.updateBasketItem');
    Route::delete('basket/item', [BasketController::class, 'deleteItem'])->name('basket.delete');
    Route::delete('basket/items', [BasketController::class, 'deleteItems'])->name('basket.deleteItems');
    Route::patch('basket/addItems', [BasketController::class, 'addItems'])->name('basket.addItems');

    Route::post('basket/items/{hash}/editModification', [BasketController::class, 'changeModification'])->name('basket.changeModification');
    Route::post('basket/items/{hash}/changeQuantity', [BasketController::class, 'changeQuantity'])->name('basket.changeQuantity');
    Route::delete('basket/items/{hash}/delete', [BasketController::class, 'deleteFromHash'])->name('basket.deleteFromHash');
    /**
     * Корзина
     */
    Route::get('basket', [BasketController::class, 'refreshAndReturnBasket'])->name('basket.getBasket');
    Route::patch('basket', [BasketController::class, 'update'])->name('basket.update');

    Route::patch('basket/changeCourier', [BasketController::class, 'changeCourier'])->name('basket.changeCourier');
    Route::patch('basket/changePickup', [BasketController::class, 'changePickUp'])->name('basket.changePickUp');
    Route::patch('basket/changePayment', [BasketController::class, 'changePaymentType'])->name('basket.changePayment');
    Route::patch('basket/addPromoCode', [BasketController::class, 'addPromoCode'])->name('basket.addPromoCode');
    Route::patch('basket/updateCallNotification', [BasketController::class, 'updateCallNotification'])->name('basket.updateCallNotification');
});

/*****************************
 *  Доступно после авторизации
 *****************************/

Route::group([
    'as'         => 'basket.',
    'middleware' => ['auth:web'],
], function () {
    Route::patch('basket/createOrder', [BasketController::class, 'createOrder'])->name('basket.createOrder');
    Route::patch('basket/repeatOrder/{id}', [BasketController::class, 'orderBasketItemRepeat'])->name('basket.orderBasketItemRepeat');

    Route::get('orders', [OrderController::class, 'index'])->name('order.getOrders');
    Route::get('orders/{id}', [OrderController::class, 'getOrder'])->name('order.getOrder');
    Route::get('orders/getActive', [OrderController::class, 'getActiveOrders'])->name('order.getActiveOrders');
    Route::get('orders/getHistory', [OrderController::class, 'getOrderHistory'])->name('order.getHistory');

    Route::get('orders/thankPage/{id}', [PaymentController::class, 'thankPage'])->name('getThankPage');
});


