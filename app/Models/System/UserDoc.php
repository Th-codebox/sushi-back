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
 * @property string $name
 * @property string $group
 * @property string $description
 * @property string $path
 * @property bool $status
 * @property bool $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $remember_token
 * @property string|null $deleted_at
 *
 * @mixin \Eloquent
 */
class UserDoc extends Model
{
    use  SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'group',
        'description',
        'path',
        'status',
        'sort_order',
        'user_id',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
