<?php


namespace App\Services\CRM\System;


use App\Repositories\System\WorkSpaceRepository;
use App\Services\CRM\CRMBaseService;
use Illuminate\Support\Carbon;

/**
 * Class WorkSpaceService
 * @package App\Services\CRM\System
 * @method WorkSpaceRepository getRepository()
 */
class WorkSpaceService extends CRMBaseService
{

    public function __construct(?WorkSpaceRepository $repository = null)
    {
        parent::__construct($repository);
    }


    public function todayUserOnSpace()
    {
        /**
         * @var WorkScheduleService $workSchedule
         */
        $workSchedule = WorkScheduleService::findOne(['workSpaceId' => $this->getRepository()->getModel()->id, 'date' => Carbon::now()->toDateString()]);

        return $workSchedule->getRepository()->getModel()->user;
    }

    public function getUsersByPhoneAccount($phoneAccount)
    {
        /**
         * @var WorkScheduleService $workSchedule
         */
        $workSchedule = WorkScheduleService::findOne(['phoneAccount' => $phoneAccount, 'date' => Carbon::now()->toDateString()]);

        return $workSchedule->getRepository()->getModel()->user;
    }
}
