<?php


namespace App\Repositories\System;

use App\Enums\ShiftTime;
use App\Models\System\WorkSchedule as WorkScheduleModel;
use App\Repositories\BaseRepository;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method WorkScheduleModel getModel()
 */
class WorkScheduleRepository extends BaseRepository
{

    protected array $relations = ['user','workSpace'];
    /**
     * WorkScheduleRepository constructor.
     * @param WorkScheduleModel|null $model
     */
    public function __construct(WorkScheduleModel $model = null)
    {

        if ($model === null) {
            $model = new WorkScheduleModel();
        }

        parent::__construct($model);
    }



    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {

    }


    public function getStaffCountForMonth($date,$filialId) {
      return  $this->getModel()->getStaffCountForMonth($date,$filialId);
    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $query = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub

        if (array_key_exists('dateMonth', $conditions) && $conditions['dateMonth'] instanceof Carbon) {
            $query->whereYear('date', '=', $conditions['dateMonth']->year)->whereMonth('date', '=', $conditions['dateMonth']->month);
        }
        if (array_key_exists('date', $conditions) && $conditions['date']) {
            $query->whereDate('date', '=', $conditions['date']);
        }

        if (array_key_exists('!date', $conditions) && $conditions['!date']) {
            $query->whereDate('date', '!=', $conditions['!date']);
        }

        if (array_key_exists('userId', $conditions) && is_numeric($conditions['userId'])) {
            $query->where('user_id', '=', $conditions['userId']);
        }

        if (array_key_exists('filialId', $conditions) && is_numeric($conditions['filialId'])) {
            $query->whereHas('workSpace', function (Builder $query)  use ($conditions){
                $query->where('filial_id','=', $conditions['filialId']);
            });
        }

        if (array_key_exists('phoneAccount', $conditions) && $conditions['phoneAccount']) {
            $query->whereHas('workSpace', function (Builder $query)  use ($conditions){
                $query->where('phone_account','=', $conditions['phoneAccount']);
            });
        }

        if (array_key_exists('role', $conditions) && $conditions['role'] === 'courier') {
            $query->whereHas('workSpace', function (Builder $query) {
                $query->where('group','=', 'Курьеры');
            });
        }

        if (array_key_exists('role', $conditions) && $conditions['role'] === 'manager') {
            $query->whereHas('workSpace', function (Builder $query) {
                /**
                 * @TODO РАСКОМЕНТИРОВАТЬ после проверок вебсокетов
                 */
             //   $query->where('group','=', 'Курьеры');
            });
        }

        if (array_key_exists('shiftTime', $conditions) && is_string($conditions['shiftTime'])) {
            $query->where('shift_time', '=', $conditions['shiftTime']);
        }
        if (array_key_exists('workSpaceId', $conditions) && is_numeric($conditions['workSpaceId'])) {
            $query->where('work_space_id', '=', $conditions['workSpaceId']);
        }
        if (array_key_exists('dateSort', $conditions) && is_string($conditions['dateSort'])) {
            $query->orderBy('date', $conditions['dateSort']);
        }

        return $query;
    }
}
