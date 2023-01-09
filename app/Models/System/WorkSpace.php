<?php



namespace App\Models\System;


use App\Models\Store\Filial;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class WorkSchedule
 * @package App\Models\System
 * @property int $id
 * @property int $filial_id
 * @property int $role_id
 * @property string $phone_account
 * @property string $group
 * @property string $name
 * @property string $description
 * @property string $additional_info
 * @property boolean $is_reserve
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Filial $filial
 * @property-read Role $role
 *
 * @mixin Eloquent
 */
class WorkSpace extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filial_id',
        'role_id',
        'name',
        'description',
        'additional_info',
        'group',
        'is_reserve',
        'phone_account',
    ];

    protected $casts = [
        'additional_info' => 'json',
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


}
