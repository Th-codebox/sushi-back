<?php

namespace App\Http\Controllers\Webhooks;

use App\Events\Order\Custom\OrderCompleteEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Webhooks\TeleStore\CallStatusRequest;
use App\Libraries\Atol\AtolApiClient;
use App\Libraries\TeleStore\IncomingCallDistributor;
use App\Libraries\TeleStore\System\CallLogger;
use App\Listeners\Order\Custom\SendOrderToAtolOnline;
use App\Models\Order\Order;
use App\Models\System\Payment\AtolReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AtolController extends Controller
{
    public function receiptStatusCallback(Request $request)
    {
        Log::channel('atol')->debug('Atol sell status', [
            'request' => $request->toArray(),
        ]);

        $uuid = $request->input('uuid');

        if ($uuid) {
            /** @var  AtolReceipt $receipt */
            $receipt = AtolReceipt::where('uuid', $uuid)->firstOrFail();
            $receipt->status = $request->input('status');
            $receipt->report_object = $request->toArray();
            $receipt->save();
        }
    }


    public function viewOrderReceipts($order_id, Request $request)
    {
        /** @var Order $order */
        $order = Order::with(['basket', 'basket.items'])->findOrFail($order_id);

        dump($order->toArray());

        $atolReceipts = AtolReceipt::where('order_id', $order->id)->get();

        dump($atolReceipts->toArray());
    }

    public function resendOrder($order_id, Request $request, AtolApiClient $atolApiClient)
    {
        /** @var Order $order */
        $order = Order::with(['basket', 'basket.items'])->findOrFail($order_id);

        $atolApiClient->sendOrder($order);

        $atolReceipts = AtolReceipt::where('order_id', $order->id)->get();

        dump($atolReceipts->toArray());

        dump($order->toArray());
    }

}
