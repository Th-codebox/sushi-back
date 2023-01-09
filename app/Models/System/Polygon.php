<?php

namespace App\Models\System;

use App\Enums\PolygonType;
use App\Models\Store\Filial;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Utm
 * @package App\Models\Order
 * @property int $id
 * @property int $filial_id
 * @property int $price
 * @property Carbon $time
 * @property int $free_from_price
 * @property string $name
 * @property string $color
 * @property PolygonType $type
 * @property \Grimzy\LaravelMysqlSpatial\Types\Polygon $area
 * @property bool $status
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Filial $filial
 */
class Polygon extends Model
{
    use SpatialTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filial_id',
        'name',
        'price',
        'free_from_price',
        'status',
        'type',
        'sort_order',
        'area',
        'time',
        'color',
    ];

    protected $spatialFields = [
        'area'
    ];

    protected $casts = [

    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filial() {
        return $this->belongsTo(Filial::class);
    }

    /**
     * @return array[]
     */
    public function getArrayCoords()
    {
        $geometries = $this->area->getGeometries();
        $polygon = reset($geometries);
        $zoneList = $polygon->getGeometries();
        return array_map(static function ($zone) {

            return ['x' => $zone->getLat(),'y' =>  $zone->getLng()];

        }, $zoneList);
    }
}
