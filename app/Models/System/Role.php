<?php


namespace App\Models\System;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Role
 *
 * @package App\Models\System
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $sort_order
 * @property boolean $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Permission $permissions
 */
class Role extends Model
{
    use SoftDeletes;

    const SuperAdmin = 1;
    const Courier = 2;
    const Manager = 3;


    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'status',
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id','id','id')
            ->withPivot('type');
    }
}
