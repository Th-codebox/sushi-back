<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class CourierDevice
 *
 * @package App\Models\System
 * @property int $id
 * @property int $user_id
 * @property string $device_id
 * @property string $agent
 * @property string $push_token
 * @property string $device
 * @property Carbon $login_at
 * @property Carbon $logout_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property-read  User $user
 *
 * @mixin \Eloquent
 */
class UserDevice extends Model
{
    use  SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent',
        'user_id',
        'push_token',
        'device',
        'os',
        'logout_at',
        'device_id',
        'login_at',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
