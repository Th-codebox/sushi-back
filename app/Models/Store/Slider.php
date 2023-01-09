<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class MenuItem
 *
 * @package App\Models\Store
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $link
 * @property string $desktop_image
 * @property string $mobile_image
 * @property string $target
 * @property bool $status
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin \Eloquent
 */
class Slider extends Model
{

    protected $table = 'sliders';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'link',
        'desktop_image',
        'mobile_image',
        'target',
        'status',
        'sort_order',
    ];

    protected $casts = [
    ];



}
