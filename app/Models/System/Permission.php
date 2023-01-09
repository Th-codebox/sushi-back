<?php


namespace App\Models\System;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Permission
 *
 * @package App\Models\System
 * @property integer $id
 * @property string $name
 * @property string $namespace
 * @property string $controller
 * @property string $method
 * @property string $group
 * @property integer $sort_order
 * @property boolean $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Permission extends Model
{
    protected $fillable = [
        'name',
        'namespace',
        'controller',
        'method',
        'group',
        'sort_order',
        'status',
    ];
}
