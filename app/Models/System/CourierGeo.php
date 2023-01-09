<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourierGeo
 * @package App\Models\System
 * @property  int $user_id
 * @property  int $lat
 * @property  int $let
 * @property  int $date
 * @property-read User $user
 */
class CourierGeo extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'lat',
        'let',
        'date',
    ];

    protected $casts = ['date' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
