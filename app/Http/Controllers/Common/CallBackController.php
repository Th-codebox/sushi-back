<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Common\CallbackRequest;
use App\Libraries\System\FilialSettings;
use App\Libraries\TeleStore\AccountRouter;
use App\Libraries\TeleStore\RestApi\Client;
use App\Libraries\TeleStore\RestApi\Commands\CallbackCommand;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallBackController extends Controller
{

    /**
     * @param CallbackRequest $request
     * @param FilialSettings $filialSettings
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Libraries\TeleStore\RestApi\Exceptions\BadCommandResponse
     * @throws \App\Libraries\TeleStore\RestApi\Exceptions\BadParamsException
     */
    public function callback(
        CallbackRequest $request,
        FilialSettings $filialSettings,
        AccountRouter $accountRouter
    )
    {
        /** @var Order $order */
        $order = Order::with(['client'])->findOrFail($request->input('id'));

        $token = $filialSettings->get('telestore.token', $order->filial_id);

        $telestoreApiClient = new Client($token);

        $userPhoneAccount = $accountRouter->getPhoneAccountByUser(Auth::user());

        $command = new CallbackCommand(
            $userPhoneAccount,
            $order->client->phone
        );

        $telestoreApiClient->sendCommand($command);

        return $this->responseSuccess(['status'=> true]);
    }

}

