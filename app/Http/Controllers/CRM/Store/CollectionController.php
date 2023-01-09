<?php


namespace App\Http\Controllers\CRM\Store;


use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\Collection\CreateCollection;
use App\Http\Requests\CRM\Collection\UpdateCollection;
use App\Http\Resources\CRM\CollectionResource;
use App\Services\CRM\Store\CollectionService;
use Illuminate\Http\Request;

class CollectionController extends BaseCRMController
{
    public function __construct(CollectionService $service)
    {
        parent::__construct($service,CreateCollection::class,UpdateCollection::class,CollectionResource::class);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function index()
    {
        return parent::index(); // TODO: Change the autogenerated stub
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function show($id)
    {
        return parent::show($id); // TODO: Change the autogenerated stub
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Http\Controller\RequestClassNotFoundException
     */
    public function store(Request $request)
    {
        return parent::store($request); // TODO: Change the autogenerated stub
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
        return parent::update($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }



}
