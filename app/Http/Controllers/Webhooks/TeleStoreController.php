<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhooks\TeleStore\CallStatusRequest;
use App\Libraries\TeleStore\IncomingCallDistributor;
use App\Libraries\TeleStore\System\CallLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeleStoreController extends Controller
{

    /**
     * @param CallStatusRequest $request
     * @param IncomingCallDistributor $callDistributor
     * @return \Illuminate\Http\JsonResponse | null
     */
    public function callStatus(
        CallStatusRequest $request,
        IncomingCallDistributor $callDistributor,
        CallLogger $logger
    )
    {

        $callDistributor->process($request);
        $callRedirect = $callDistributor->getRedirectData();

        $log = $callDistributor->getLog();

        Log::channel('telestore')->debug('Incoming call webhook', [
            'request' => $request->all(),
            'distributorLog' => $log,
            'callRedirect' => $callRedirect
        ]);




        if ($callRedirect) {
            //return $this->responseSuccess($callRedirect);
            return new JsonResponse($callRedirect, 200, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
        }
    }
}
