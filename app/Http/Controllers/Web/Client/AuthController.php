<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\SendCode;
use App\Http\Requests\Login;
use App\Http\Resources\Web\ClientResource;
use App\Http\ResponseTrait;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\System\ClientDeviceService;
use App\Services\CRM\System\ClientService;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{


    protected CRMBaseService $service;
    protected string $guard = 'web';


    /**
     * AuthController constructor.
     * @param ClientService $service
     * @param string $resource
     */
    public function __construct(ClientService $service, string $resource = ClientResource::class)
    {
        $this->service = $service;
        $this->resource = $resource;

        $this->middleware('auth:' . $this->guard, ['except' => ['login', 'sendCode', 'refresh']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function sendCode(Request $request)
    {
        $request = SendCode::createFrom($request);

        $params = $request->all();

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails()) {
            return $this->responseError('Неверные параметры', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }


        $sendStatus = ClientService::sendCode($params['phone']);

        if (array_key_exists('send', $sendStatus) && $sendStatus['send'] === true) {
            return $this->responseSuccess($sendStatus);
        } else {
            return $this->responseError($sendStatus['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function login(Request $request)
    {
        $request = Login::createFrom($request);

        $params = $request->all();

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails()) {
            return $this->responseError('Неверные параметры', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }

        $credentials = $request->only(['phone', 'password']);

        try {
            $userService = $this->service::findOne(['phone' => $credentials['phone']]);
        } catch (\Throwable $e) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        $userData = $userService->getRepository()->getModel();


        if ($userData->code_last_send_at->timestamp + 360 < now()->timestamp) {
            return $this->responseError('Код актуален 360 секунд, отправьте новый код и подтвердите еще раз', 403);
        }

        if (!$token = auth($this->guard)->attempt($credentials, true)) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        activity()
            ->performedOn($userData)
            ->causedBy($userData)
            ->withProperties(['utms' => $params['utms'] ?? null])
            ->log('Client log-in');


        if (array_key_exists('deviceInfo', $params) && is_array($params['deviceInfo']) && $params['deviceInfo']) {
            try {

                $clientDevice = ClientDeviceService::findOne(['clientId' => $userData->id, 'deviceId' => $params['deviceInfo']['deviceId'] ?? null, 'device' => $params['deviceInfo']['device'] ?? null, 'agent' => $params['deviceInfo']['agent'] ?? null, 'logoutAt' => null]);

                $clientDevice->edit(['loginAt' => Carbon::now()]);

            } catch (\Throwable $e) {
                $params['deviceInfo']['clientId'] = $userData->id;
                ClientDeviceService::add($params['deviceInfo']);
            }
        }

        if(!$userData->confirm_phone) {
            $userService->edit(['confirmPhone' => true]);
        }


        return $this->responseSuccess([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data'       => new $this->resource($userData),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function me()
    {
        try {
            $userService = $this->service::findOne(['id' => auth()->user()->id]);
        } catch (\Throwable $e) {
            return $this->responseError('Клиент не найден', Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithItem($userService->getRepository()->getModel());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        $params = $request->all();

        try {
            if (array_key_exists('deviceInfo', $params) && is_array($params['deviceInfo']) && $params['deviceInfo']) {
                $clientDevice = ClientDeviceService::findOne(['clientId' => auth()->user()->id, 'deviceId' => $params['deviceInfo']['deviceId'] ?? null, 'device' => $params['deviceInfo']['device'] ?? null, 'agent' => $params['deviceInfo']['agent'] ?? null, 'logoutAt' => null]);
                $clientDevice->edit(['logoutAt' => Carbon::now()]);
            }
        } catch (\Throwable $e) {

        }


        auth()->logout();


        return $this->responseSuccess(['message' => 'Выход из системы прошёл успешно']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->responseSuccess([
                'token'      => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
