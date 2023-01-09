<?php

namespace App\Http\Controllers\CRM\System;

use App\Enums\OrderStatus;
use App\Events\Cooking\UpdateAssemblyCookingSchedule;
use App\Events\Cooking\UpdateColdCookingSchedule;
use App\Events\Cooking\UpdateHotCookingSchedule;
use App\Http\Controllers\CRM\BaseCRMController;
use App\Models\System\User;
use App\Services\CRM\System\CookingScheduleService;
use App\Services\CRM\System\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\CRM\CookingSchedule\{UpdateCookingScheduleRequest, StoreCookingScheduleRequest};
use App\Http\Resources\CRM\CookingScheduleResource;


class CookingScheduleController extends BaseCRMController
{
    public function __construct(CookingScheduleService $service)
    {
        parent::__construct($service, StoreCookingScheduleRequest::class, UpdateCookingScheduleRequest::class, CookingScheduleResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getHots()
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        $user->searchActualFilial();

        $filialId = request()->get('filialId', $user->actualFilial->id ?? null);

        return $this->respondWithCollection($this->service->findListAsCollectionModel([
            'filialId'       => $filialId,
            'orderStatus'    => OrderStatus::Preparing,
            'hotIsCompleted' => false,
        ]));
    }


    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function completeHot($orderId)
    {

        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        //$user->searchActualFilial();


        //try {
            $service = $this->service::findOne([
                'orderId'  => $orderId,
                //'filialId' => $user->actualFilial->id ?? null,
            ]);
        /*} catch (\Throwable $e) {
            return $this->responseError('Действие не доступно');
        }*/

        $service->edit(['hotIsCompleted' => true]);

        UpdateHotCookingSchedule::dispatch($service->getRepository()->getModel(), Auth()->user());

        return $this->getHots();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getColds()
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        $user->searchActualFilial();

        $filialId = request()->get('filialId', $user->actualFilial->id ?? null);

        return $this->respondWithCollection($this->service->findListAsCollectionModel([
            'filialId'        => $filialId,
            'orderStatus'     => OrderStatus::Preparing,
            'coldIsCompleted' => false,
        ]));
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function completeCold($orderId)
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        //$user->searchActualFilial();


        //try {
            $service = $this->service::findOne([
                'orderId'  => $orderId,
                //'filialId' => $user->actualFilial->id ?? null,
            ]);
        /*} catch (\Throwable $e) {
            return $this->responseError('Действие не доступно');
        }*/

        $service->edit(['coldIsCompleted' => true]);

        UpdateColdCookingSchedule::dispatch($service->getRepository()->getModel(), Auth()->user());

        return $this->getColds();
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getAssembly()
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        $user->searchActualFilial();

        $filialId = request()->get('filialId', $user->actualFilial->id ?? null);

        return $this->respondWithCollection($this->service->findListAsCollectionModel([
            'filialId'            => $filialId,
            'orderStatus'         => OrderStatus::Assembly,
            'assemblyIsCompleted' => false,
            'coldIsCompleted'     => true,
            'hotIsCompleted'      => true,
        ]));

    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function completeAssembly($orderId)
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => auth()->user()->id])->getRepository()->getModel();

        //$user->searchActualFilial();

       /* try {*/

            $service = $this->service::findOne([
                'orderId'             => $orderId,
                //'filialId'            => $user->actualFilial->id ?? null,
                'coldIsCompleted'     => true,
                'hotIsCompleted'      => true,
                'assemblyIsCompleted' => false,
            ]);
       /* } catch (\Throwable $e) {
            return $this->responseError('Действие не доступно');
        }*/

        $service->edit(['assemblyIsCompleted' => true]);

        UpdateAssemblyCookingSchedule::dispatch($service->getRepository()->getModel(), Auth()->user());

        return $this->getAssembly();
    }
}
