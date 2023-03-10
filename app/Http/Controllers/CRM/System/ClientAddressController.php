<?php


namespace App\Http\Controllers\CRM\System;


use App\Http\Controllers\CRM\BaseCRMController;

use App\Http\Requests\CRM\Client\CreateAddress;
use App\Http\Requests\CRM\Client\UpdateAddress;
use App\Http\Resources\CRM\ClientAddressResource;
use App\Services\CRM\System\ClientAddressService;
use Illuminate\Http\Request;

class ClientAddressController extends BaseCRMController
{

    protected function conditions(): array
    {
        $data =  parent::conditions(); // TODO: Change the autogenerated stub

        $data['clientId'] = request()->input('clientId');


        return  $data;
    }

    /**
     * ClientAddressController constructor.
     * @param ClientAddressService $service
     */
    public function __construct(ClientAddressService $service)
    {
        parent::__construct($service, CreateAddress::class, UpdateAddress::class, ClientAddressResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
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
     * @throws \App\Repositories\RepositoryException
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
