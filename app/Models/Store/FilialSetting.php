<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Filial
 *
 * @package App\Models
 * @property int $id
 * @property int $setting_id
 * @property int $filial_id
 * @property string $value
 * @property boolean $json
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read  Setting $setting
 */
class FilialSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filial_settings';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filial_id',
        'setting_id',
        'value',
        'json',
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
        'json' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultSeting() {
        return $this->belongsTo(Setting::class,'id','setting_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filial() {
        return $this->belongsTo(Setting::class,'id','filial_id');
    }
}
