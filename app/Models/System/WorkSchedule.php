<?php

namespace App\Models\System;

use App\Enums\ShiftTime;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class WorkSchedule
 * @package App\Models\System
 * @property int $id
 * @property int $user_id
 * @property Carbon $date
 * @property Carbon $begin
 * @property Carbon $end
 * @property ShiftTime $shift_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read WorkSpace $workSpace
 *
 * @mixin Model
 */
class WorkSchedule extends Model
{
    use CastsEnums, LogsActivity;

    protected static $logAttributes = [
        'user_id',
        'work_space_id',
        'date',
        'shift_time',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'work_space_id',
        'date',
        'begin',
        'end',
        'shift_time',
    ];

    protected $spatialFields = [
        'shift_time' => ShiftTime::class,
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->with('transactions');
    }


    public function workSpace()
    {
        return $this->belongsTo(WorkSpace::class);
    }

    public function getStaffCountForMonth(Carbon $date, int $filialId = null)
    {

        return $this->newQuery()
            ->with('workSpace')
            ->whereHas('workSpace', function (\Illuminate\Database\Eloquent\Builder $query)  use ($filialId){
                if($filialId !== null) {
                    $query->where('filial_id','=', $filialId);
                }

             })
            ->whereYear('date', '=', $date->year)
            ->whereMonth('date', '=', $date->month)
            ->select('date', DB::raw('count(*) as usersCount'))
            ->groupBy('date')
            ->get()->toArray();
    }

}
