<?php

namespace App\Models\System;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ClientAddress
 * @package App\Models\System
 *
 * @property int $id
 * @property int $client_id
 * @property string $apartment_number
 * @property string $ico_name
 * @property string $name
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $entry
 * @property string $floor
 * @property string $lat_geo
 * @property string $let_geo
 * @property string $break_address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Client $client
 *
 */
class ClientAddress extends Model
{
    use SoftDeletes;

    protected $table = 'client_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'street',
        'ico_name',
        'house',
        'entry',
        'lat_geo',
        'let_geo',
        'client_id',
        'name',
        'apartment_number',
        'floor',
        'break_address',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function getFullAddress()
    {
        return 'Россия, ' . $this->city . ', ' . $this->street . ', д.' . $this->house . ', ' .
            ($this->entry ? '  п.' . $this->entry . ', ' : '') .
            ($this->floor ? '  э.' . $this->floor . ', ' : '') .
            ($this->apartment_number ? '  кв.' . $this->apartment_number : '');
    }

    public function asFormattedString(): string
    {
        $addressChunks = [
            $this->city,
            $this->street,
            'д. ' . $this->house,
        ];

        if ($this->apartment_number) {
            $addressChunks[] = 'кв. ' . $this->apartment_number;
        }

        if ($this->entry) {
            $addressChunks[] = 'подъезд ' . $this->entry;
        }

        return implode(", ", $addressChunks);
    }


}
