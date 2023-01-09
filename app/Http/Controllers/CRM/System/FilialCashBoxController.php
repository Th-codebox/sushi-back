<?php

namespace App\Http\Controllers\CRM\System;

use App\Http\Controllers\CRM\BaseCRMController;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\CRMServiceInterface;
use App\Services\CRM\System\WorkScheduleService;
use Illuminate\Support\Carbon;
use App\Http\Requests\CRM\FilialCashBox\{UpdateFilialCashBoxRequest, StoreFilialCashBoxRequest};
use App\Http\Resources\CRM\FilialCashBoxResource;
use App\Services\CRM\System\FilialCashBoxService;


class FilialCashBoxController extends BaseCRMController
{
    public function __construct(FilialCashBoxService $service)
    {
        parent::__construct($service, StoreFilialCashBoxRequest::class, UpdateFilialCashBoxRequest::class, FilialCashBoxResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function closeCashBox()
    {

        $userId = auth()->user()->id;

        $closeStatus = $this->service->close($userId);

        if ($closeStatus) {
            return $this->responseSuccess(['message' => 'Касса успешно закрыта']);
        }

        return $this->responseError('Не удалось найти открытую кассу');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function getBalanceAndInformationByOpenCashBox()
    {
        /**
         * @var FilialCashBoxService $openCashBoxService
         */
        $date = request()->input('date', null);

        $filialId = request()->input('filialId', 1);
        $openCashBoxService = $this->findOpenCashBox($date,$filialId);

        return $this->responseSuccess(['data' => $openCashBoxService->getAllInfo($date)]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function getShortInfo()
    {
        $date = request()->input('date', null);
        $filialId = request()->input('filialId', 1);

        try {
            $cashBox = $this->findOpenCashBox($date,$filialId);
            $data = $cashBox->getShortInfo($date);

        } catch (\Throwable $e) {

            $data = [
                'totalProceed' => 0,
                'totalOrder'   => 0,
            ];
        }

        return $this->responseSuccess(['data' => $data]);
    }

    /**
     * @param string|null $date
     * @param $filialId
     * @return CRMServiceInterface
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     */
    private function findOpenCashBox(string $date = null,$filialId): CRMServiceInterface
    {
        $conditions['filialId'] = $filialId;
        $conditions['sort'] = 'new';

        if ($date) {
            $conditions['date'] = $date;
        } else {
            $conditions['closeAt'] = null;
        }


        try {
            $service =  $this->service::findOne($conditions);

        } catch (\Throwable $e) {
            unset($conditions['date']);
            $conditions['closeAt'] = null;
            $service =  $this->service::findOne($conditions);
        }
        return $service;
    }

}
