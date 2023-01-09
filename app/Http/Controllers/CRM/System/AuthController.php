<?php

namespace App\Http\Controllers\CRM\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Resources\CRM\UserResource;
use App\Models\System\User;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\System\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    protected CRMBaseService $service;
    protected string $guard = 'crm';

    /**
     * AuthController constructor.
     * @param UserService $service
     * @param string $resource
     */
    public function __construct(UserService $service, string $resource = UserResource::class)
    {
        $this->service = $service;
        $this->resource = $resource;

        $this->middleware('auth:' . $this->guard, ['except' => ['login', 'refresh']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {

        $request = Login::createFrom($request);

        $params = $request->all();

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails()) {
            return $this->responseError('Неверные параметры', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }

   //     activity()->log('Look mum, I logged something');
        $credentials = $request->only(['phone', 'password']);

        try {
            $userService = $this->service::findOne(['phone' => $credentials['phone']]);
        } catch (\Throwable $e) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        $userData = $userService->getRepository()->getModel();

        if (!$userData->status) {
            //return $this->responseError('Пользователь не активирован', 402);
        }

        if (!$token = auth($this->guard)->attempt($credentials, true)) {
            return $this->responseError('Неверный логин или пароль', Response::HTTP_UNAUTHORIZED);

        }

        return $this->responseSuccess([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' =>  new $this->resource($userData)
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {

        try {
            $userService = $this->service::findOne(['id' => auth()->user()->id]);
        } catch (\Throwable $e) {
            return $this->responseError('Пользователь не найден', Response::HTTP_NOT_FOUND);
        }

        return $this->respondWithItem($userService->getRepository()->getModel());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
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


