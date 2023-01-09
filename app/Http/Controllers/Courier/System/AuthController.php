<?php

namespace App\Http\Controllers\Courier\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Resources\Courier\CourierProfileResource;
use App\Models\System\User;
use App\Services\CRM\System\UserDeviceService;
use App\Services\CRM\System\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{


    protected UserService $service;
    protected string $guard = 'crm';

    /**
     * AuthController constructor.
     * @param UserService $service
     * @param string $resource
     */
    public function __construct(UserService $service, string $resource = CourierProfileResource::class)
    {
        $this->service = $service;
        $this->resource = $resource;

        $this->middleware('auth:' . $this->guard, ['except' => ['login', 'refresh']]);
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
            return $this->responseError('Неверные параметры',
                Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }

        //     activity()->log('Look mum, I logged something');
        $credentials = $request->only(['phone', 'password']);

        try {
            $userService = $this->service::findOne(['phone' => $credentials['phone']]);
        } catch (\Throwable $e) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        /**
         * @var User $courierModel
         */
        $courierModel = $userService->getRepository()->getModel();

        if (!$courierModel->status) {
            return $this->responseError('Пользователь не активирован', 402);
        }

        if (!$token = auth($this->guard)->attempt($credentials, true)) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        $userService->edit(['lastVisitAt' => Carbon::now()]);

        if (array_key_exists('deviceInfo', $params) && is_array($params['deviceInfo']) && $params['deviceInfo']) {
            try {
                $clientDevice = UserDeviceService::findOne(['userId' => $courierModel->id, 'deviceId' => $params['deviceInfo']['deviceId'] ?? null, 'logoutAt' => null]);
                $clientDevice->edit(['loginAt' => Carbon::now()]);
            } catch (\Throwable $e) {
                $params['deviceInfo']['userId'] = $courierModel->id;
                UserDeviceService::add($params['deviceInfo']);
            }
        }

        return $this->responseSuccess([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' =>  new $this->resource($courierModel)
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->respondWithItem(auth()->user());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $params = $request->all();

        try {
            if (array_key_exists('deviceInfo', $params) && is_array($params['deviceInfo']) && $params['deviceInfo']) {
                $clientDevice = UserDeviceService::findOne(['userId' => auth()->user()->id, 'deviceId' => $params['deviceInfo']['deviceId'] ?? null, 'logoutAt' => null]);
                $clientDevice->edit(['logoutAt' => Carbon::now()]);
                $clientDevice->delete();
            }
        } catch (\Throwable $e) {

        }
        auth()->logout();
        return $this->responseSuccess(['message' => 'Выход из системы прошёл успешно']);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $params = $request->all();

        try {
            $refresh =  [
                'token'      => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ];

            if (array_key_exists('deviceInfo', $params) && is_array($params['deviceInfo']) && $params['deviceInfo']) {
                try {
                    $clientDevice = UserDeviceService::findOne(['deviceId' => $params['deviceInfo']['deviceId'] ?? null, 'logoutAt' => null]);
                    $clientDevice->edit($params['deviceInfo']);
                } catch (\Throwable $e) {
                }
            }
            return $this->responseSuccess($refresh);
        } catch (\Throwable $e) {
            return $this->responseError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}

