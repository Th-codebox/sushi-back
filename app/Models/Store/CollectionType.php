<?php

namespace App\Models\Store;

use App\Enums\CollectionType as EnumCollectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Collection
 *
 * @package App\Models\Store
 * @property int $id
 * @property int $collection_id
 * @property EnumCollectionType $value
 * @mixin \Eloquent
 */
class CollectionType extends Model
{
 //   use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'collection_id',
        'value',
    ];

    protected $casts = [
        'value'      => EnumCollectionType::class, // Example enum cast
    ];


    public function collection() {
        return $this->belongsTo(Collection::class);
    }



}
