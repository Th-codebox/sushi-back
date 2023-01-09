<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Call
 * @package App\Models\System
 * @property string call_id
 * @property int account_id
 * @property string client_phone
 * @property string incoming_phone
 * @property bool is_active_client
 * @property string client_name
 * @property int client_id
 * @property bool has_order_today
 */
class Call extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'call_id',
        'account_id',
        'client_phone',
        'incoming_phone',
        'is_active_client',
        'client_name',
        'client_id',
        'has_order_today',
    ];

}
