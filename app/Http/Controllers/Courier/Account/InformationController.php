<?php


namespace App\Http\Controllers\Courier\Account;


use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\Information\GetTimeTable;
use App\Http\Requests\Courier\Information\SaveCord;
use App\Http\Resources\Courier\CourierProfileResource;
use App\Http\Resources\Courier\CourierWorkScheduleResource;

use App\Services\CRM\System\CourierGeoService;
use App\Services\CRM\System\UserService;
use App\Services\CRM\System\WorkScheduleService;
use App\Services\Web\Client\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InformationController extends Controller
{

    protected WorkScheduleService $workScheduleService;
    protected UserService $courierService;

    public function __construct(WorkScheduleService $workScheduleService, UserService $courierService)
    {

        $this->workScheduleService = $workScheduleService;
        $this->courierService = $courierService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function getTimeTable(GetTimeTable $request)
    {
        $date = null;

        if ($request->get('date')) {
            try {
                $date = Carbon::createFromTimestamp($request->get('date'));
            } catch (\Throwable $e) {
                return $this->responseError('Неправильный формат даты, задайте дату в формате unixtime ', 402, null, get_class($e));
            }
        }

        $workSchedulesCollection = $this->workScheduleService->getTimetableForCourier(auth()->user()->id, $date);

        $this->resource = CourierWorkScheduleResource::class;
        return $this->respondWithCollection($workSchedulesCollection);
    }

    public function getProfile()
    {
        $this->resource = CourierProfileResource::class;
        return $this->respondWithItem(auth()->user());
    }

    /**
     * @param SaveCord $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function saveCord(SaveCord $request)
    {

        $data = $request->all();
        $data['userId'] = auth()->user()->id;
        $data['date'] = Carbon::now();
        CourierGeoService::add($data);
        return $this->responseSuccess(['message' => 'success']);
    }
}
