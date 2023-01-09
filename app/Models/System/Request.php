<?php

namespace App\Models\System;

use App\Enums\RequestType;
use App\Models\Store\Filial;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Request
 * @package App\Models
 *
 * @property int $id
 * @property int $filial_id
 * @property int $client_id
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property bool $is_send
 * @property array $additional_info
 * @property RequestType $type
 * @property Client $client
 * @property Filial $filial
 */
class Request extends Model
{

    protected $fillable = [
        'type',
        'filial_id',
        'client_id',
        'email',
        'phone',
        'name',
        'is_send',
        'additional_info',
    ];

    protected $casts = [
        'type'            => RequestType::class,
        'additional_info' => 'json',
        'is_send'         => 'bool',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
