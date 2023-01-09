<?php

namespace App\Models\System;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


/**
 * Class CookingSchedule
 *
 * @package App\Models\System
 * @property int $id
 * @property bool $cold_is_completed
 * @property bool $hot_is_completed
 * @property bool $assembly_is_completed
 * @property ?Carbon $time_begin_assembly
 * @property int $order_id
 * @property ?Carbon $deadLine
 * @property Order $order
 * @property ?Carbon $created_at

 *
 * @mixin \Eloquent
 */
class CookingSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'cooking_schedules';

    public ?Carbon $deadLine;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time_begin_assembly',
        'cold_is_completed',
        'hot_is_completed',
        'assembly_is_completed',
        'order_id',
    ];

    protected $casts = [
        'time_begin_assembly' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id')->with('basket')->with('filial');
    }

    public function calcDeadLine()
    {
        $this->deadLine = Carbon::Now()->addMinutes(15);
    }
}
