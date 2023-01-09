<?php


namespace App\Http\Controllers\CRM\System;


use App\Http\Controllers\CRM\BaseCRMController;

use App\Http\Requests\CRM\User\CreateUser;
use App\Http\Requests\CRM\User\UpdateUser;
use App\Http\Resources\Courier\CourierBalanceResource;
use App\Http\Resources\CRM\UserResource;
use App\Services\CRM\System\UserService;
use Illuminate\Http\Request;

class UserController extends BaseCRMController
{

    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service, CreateUser::class, UpdateUser::class, UserResource::class);
    }

    public function getBalance(int $userId)
    {

        /**
         * @var UserService $service
         */
        $service = $this->service::findOne(['id' => $userId]);

        $service->calcBalance();

        $this->resource = CourierBalanceResource::class;

        return $this->respondWithItem($service->getRepository()->getModel());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function index()
    {
        return parent::index();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Http\Controller\RequestClassNotFoundException
     * @throws \ReflectionException
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Http\Controller\RequestClassNotFoundException
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function show($id)
    {
        return parent::show($id);
    }


}
