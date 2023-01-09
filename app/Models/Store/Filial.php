<?php

namespace App\Models\Store;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Filial
 *
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $requisites
 * @property string $address
 * @property string $lat
 * @property string $let
 * @property string $work_phone
 * @property integer $min_order_cost
 * @property integer $sort_order
 * @property bool $status
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read FilialSetting $settings
 */
class Filial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filials';




    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'requisites',
        'address',
        'min_order_cost',
        'status',
        'sort_order',
        'work_phone',
        'lat',
        'let',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings() {
        return $this->hasMany(FilialSetting::class,'filial_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(Order::class,'filial_id','id');
    }
}
