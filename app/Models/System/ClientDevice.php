<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class User
 *
 * @package App\Models\System
 * @property int $id
 * @property string $agent
 * @property string $push_token
 * @property string $device_id
 * @property string $device
 * @property Carbon $login_at
 * @property Carbon $logout_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $remember_token
 * @property string|null $deleted_at
 *
 * @mixin \Eloquent
 */
class ClientDevice extends Model
{
    use  SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent',
        'client_id',
        'push_token',
        'device',
        'os',
        'logout_at',
        'login_at',
        'device_id',
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
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
