<?php

use App\Http\Controllers\CRM\Order\OrderController;
use App\Http\Controllers\CRM\Reports\KitchenStatController;
use App\Http\Controllers\CRM\Reports\OrdersStatController;
use App\Http\Controllers\CRM\System\PrintingController;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Services\CRM\System\FilialCashBoxService;
use App\Services\Payment\PaymentStatusService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/****************************
 *  Доступно без авторизации
 ****************************/

Route::get('/summary', [ OrdersStatController::class, 'receiveSummary' ]);

Route::get('/couriers', [ OrdersStatController::class, 'couriersOrders' ]);

Route::get('/ordered-products', [ OrdersStatController::class, 'orderedProducts' ]);

Route::get('payments', function (\App\Services\Payment\PaymentStatusService $paymentStatusService) {
    /*dump(\App\Models\Order\Order::with('payment')->whereIn('id', [
        526,
        528,
        529,
        530,
        531,
        532
    ])->get()->toArray());*/
    $paymentStatusService->updateAllWaitingPaymentOrders();
});

Route::get('payments/{orderId}', function ($orderId, PaymentGateway $gateway) {
    $order = \App\Models\Order\Order::with('payment')->findOrFail($orderId);

    dump($order->toArray());

    dump($gateway->getStatus($order));

    //dump($gateway->cancelOrder($order));
});

Route::get('cash-box/{filialId}/{date}', function ($filialId, $date) {

    /** @var FilialCashBoxService $service */
    $service = FilialCashBoxService::findOne([
        'filialId' => $filialId,
        'date' => $date
    ]);

    dump($service->getRepository()->getModel()->toArray());

    dd($service->getAllInfo($date));
});

/*Route::get('orders', function (\App\Http\Requests\CRM\Reports\DateInterval $request) {
    $controller = App::make(\App\Http\Controllers\CRM\Order\OrderController::class);
    $controller->index();
    return view('empty');

});*/

//Route::get('orders/{id}/singleMenuItems', [OrderController::class, 'getSingleMenuItems'])->name('getSingleMenuItems');

//Route::get('printing/print-sticker', [PrintingController::class, 'printSticker']);

//Route::resource('orders', 'CRM\Order\OrderController');

Route::get('payments-check', function (PaymentStatusService $paymentStatusService) {
    $paymentStatusService->updateAllWaitingPaymentOrders();
});

Route::get('kitchen/cells', [ KitchenStatController::class, 'cellReservation' ]);


