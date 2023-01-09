<?php

use App\Enums\DeliveryType;
use App\Http\Controllers\CRM\System\PrintingController;
use App\Http\Controllers\Webhooks\AtolController;
use App\Http\Middleware\ForceAcceptJson;

use App\Libraries\Atol\AtolApiClient;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Libraries\System\FilialSettings;
use App\Models\System\Client;
use App\Models\System\Payment\AtolReceipt;
use App\Models\System\User;
use App\Notifications\Courier\OrderCanceled;
use App\Services\Web\Client\ClientAddressService;
use Fomvasss\Dadata\Facades\DadataClean;
use Fomvasss\Dadata\Facades\DadataSuggest;
use Illuminate\Support\Facades\Log;
use App\Models\Order\Order;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhooks\TeleStoreController;
use Illuminate\Http\Request;


/****************************
 *  Доступно без авторизации
 ****************************/

/* TeleStore Webhook */
Route::post('telestore/call', [ TeleStoreController::class, 'callStatus' ])->name('telestore.call');

/* Atol callback */
Route::post('atol/status', [ AtolController::class, 'receiptStatusCallback' ])->name('atol.status');




Route::group([
    'excluded_middleware' => [ForceAcceptJson::class],
], function () {

    /* Просмотр чеков по заказу */
    Route::get('atol/order/{order_id}', [ AtolController::class, 'viewOrderReceipts' ]);

    /* Отправить чек в Атол заново */
    Route::get("atol/resend/{order_id}", [ AtolController::class, 'resendOrder' ]);




    /* TEST */

    /*Route::get('test', function (Request $request) {
        return view('test');
    });*/


    //Route::get('order/{id}/sms', function ($id, Request $request) {

        /* Отправка временному клиенту без его сохранения */
        /*$client = new App\Models\System\Client();
        $client->id = 77777; //(нужно только при выборе драйвера database)
        $client->phone = "000";
        $client->notify(new \App\Notifications\AuthCode("777"));*/

        /* Отправка клиенту из базы */
        /*$client = \App\Models\System\Client::find(1);
        $client->notify(new \App\Notifications\AuthCode("777"));*/


        /* Отправка на произвольный номер (на крайний случай) */
       /*Notification::route('sms', '79602837723')
          ->notify(new \App\Notifications\AuthCode("Hello)"));*/


        /** @var \App\Models\System\Courier $courier */
        //$courier = \App\Models\System\Courier::firstOrFail();

        /** @var Order $order */
        /* $order = Order::findOrFail($id);

        dump($order->client->toArray());

        $order->client->notify(new \App\Notifications\Client\NewOrder($order));


       //$courier->notify(new \App\Notifications\Courier\OrderCanceled($order));

    });*/



    Route::get('order/{order_id}', function ($order_id, Request $request) {
        $order = Order::with('basket')->findOrFail($order_id);
        event(new \App\Events\Order\UpdateOrder($order, null));
        event(new \App\Events\Order\UpdateStatusesOrder($order, null));
    });

    /*Route::get('order/canceled/{order_id}', function ($order_id, Request $request) {

        /** @var Order $order *
        $order = Order::with(['courier', 'courier.devices'])->findOrFail($order_id);

        dump($order->toArray());

        if ($order->courier) {
            dump(['routeNotificationForFcm' => $order->courier->routeNotificationForFcm()]);
        }

        event(new \App\Events\Order\ChangeOrderStatus($order, null));

        if ($order->delivery_type == DeliveryType::Delivery && $order->courier) {
            dump(['courier notify' => $order->courier->id]);
            $order->courier->notify(new OrderCanceled($order));
        }

    });*/

    Route::get('pay-test', function (Request $request, PaymentGateway $gateway) {

        $order = \App\Models\Order\Order::with('basket')->find(60);


        $response = $gateway->registerOrder(
            $order,
            config('app.web_url')."/thankyoupage/{$order->id}?pay=ok",
            config('app.web_url')."/thankyoupage/{$order->id}?pay=fail"
        );
        $url = $response->getPaymentUrl();

        echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
    });


    Route::get('pay-status/{orderId}', function ($orderId, Request $request, PaymentGateway $gateway) {

        $order = \App\Models\Order\Order::with('basket')->find($orderId);
        $status = $gateway->getStatus($order);
        dump($status);
    });

    Route::get('printing/print-check', [ PrintingController::class, 'printCheck']);

    Route::get('phone/status/{account}', function ($account, Request $request, FilialSettings $filialSettings, \App\Libraries\TeleStore\AccountRouter $accountRouter) {

        dd($accountRouter->getUsersByPhoneAccount($account));




        /*$token = $filialSettings->getDefault('telestore.token');

        $telestoreApiClient = new \App\Libraries\TeleStore\RestApi\Client($token);
        $command = new \App\Libraries\TeleStore\RestApi\Commands\CallbackCommand(
            2000,
            '79602837723'
        );

        $telestoreApiClient->sendCommand($command);*/


    });


    Route::get('call/{number}', function ($number, Request $request) {

        $user = User::findOrNew(1);

        switch ($number) {
            case 1: $class = \App\Events\Phone\IncomingCallEvent::class; break;
            case 2: $class = \App\Events\Phone\AnswerCallEvent::class; break;
            case 3: $class = \App\Events\Phone\HangupCallEvent::class; break;
        }
        event(new $class(
            '12312',
            2000,
            "12312312",
            12312312,
            true,
            "Максим - тест",
            44,
            false,
            [ $user ]
        ));
        echo 'Event send';

    });

    Route::get('call/anonim/{number}', function ($number, Request $request) {

        $user = User::findOrNew(1);

        switch ($number) {
            case 1: $class = \App\Events\Phone\IncomingCallEvent::class; break;
            case 2: $class = \App\Events\Phone\AnswerCallEvent::class; break;
            case 3: $class = \App\Events\Phone\HangupCallEvent::class; break;
        }
        event(new $class(
            '12312',
            2000,
            "12312312",
            12312312,
            false,
            "",
            0,
            false,
            [ $user ]
        ));
        echo 'Event send';

    });


    Route::get('address/{address}', function ($address) {
        $suggests = DadataSuggest::suggest("address", [
            "query" => $address,
            "locations_geo" => [
                [
                    'lat' => '59.940352269548534',
                    'lon' => ' 30.316778585021982',
                    'radius_meters' => '30000'
                ]
            ],
        ]);

        dd($suggests);
    });

    Route::get('address/clean/{address}', function ($address) {
        dd(DadataClean::cleanAddress($address));
    });


    Route::get(
        '/stat',
        [
            \App\Http\Controllers\CRM\Reports\OrdersStatController::class,
            'receiveSummary'
        ]
    );



});



