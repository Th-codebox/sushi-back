<?php

namespace App\Http\Controllers\CRM\Store;

use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\Slider\CreateSlider;
use App\Http\Requests\CRM\Slider\UpdateSlider;
use App\Http\Resources\CRM\SliderResource;
use App\Services\CRM\Store\SliderService;
use Illuminate\Http\Request;

class SliderController extends BaseCRMController
{
    public function __construct(SliderService $service)
    {
        parent::__construct($service,CreateSlider::class,UpdateSlider::class,SliderResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
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
