<?php

namespace App\Models\System\Payment;

use App\Casts\SerialisedObject;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AtolReceipt
 * @package App\Models\System\Payment
 *
 * @property $request
 * @property $uuid'
 * @property $order_id
 * @property $request_object
 * @property $status
 * @property $report_object
 */
class AtolReceipt extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'order_id',
        'request_object',
        'status',
        'report_object'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_object' => SerialisedObject::class,
        'report_object' =>  SerialisedObject::class
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }


}
