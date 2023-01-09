<?php


namespace App\Http\Controllers\CRM\System;


use App\Http\Controllers\CRM\BaseCRMController;

use App\Http\Requests\CRM\Role\CreateRole;
use App\Http\Requests\CRM\Role\UpdateRole;
use App\Http\Resources\CRM\RoleResource;
use App\Services\CRM\System\RoleService;
use Illuminate\Http\Request;

class RoleController extends BaseCRMController
{

    /**
     * RoleController constructor.
     * @param RoleService $service
     */
    public function __construct(RoleService $service)
    {
        parent::__construct($service, CreateRole::class, UpdateRole::class, RoleResource::class);
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
