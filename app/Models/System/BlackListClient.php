<?php

namespace App\Models\System;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BlackListClient
 * @package App\Models\System
 * @property int $id
 * @property int $client_id
 * @property string $reason
 * @property boolean $status_block
 * @property Carbon $end_blocking
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Client $client
 */
class BlackListClient extends Model
{
    use SoftDeletes;

    protected $table = 'black_list_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_block',
        'end_blocking',
        'reason',
        'client_id',
    ];

    public function client(){
        return $this->hasOne(Client::class,'id','client_id');
    }


}
