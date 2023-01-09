<?php

namespace App\Models\System;

use App\Models\System\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Utm
 * @package App\Models\Order
 * @property int $id
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Utm extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'traffic_type',
    ];
}
