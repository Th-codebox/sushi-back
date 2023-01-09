<?php

namespace App\Models\Store;

use App\Enums\CookingType;
use App\Enums\DishType;
use App\Enums\ManufacturerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class TechnicalCard
 *
 * @package App\Models\Store
 * @property int $id
 * @property string $name
 * @property int $weight
 * @property int $proteins
 * @property int $fats
 * @property int $carbohydrates
 * @property int $calories
 * @property string $composition
 * @property string $composition_for_cook
 * @property string $chef_comment
 * @property CookingType $cooking_type
 * @property DishType $dish_type
 * @property ManufacturerType $manufacturer_type
 * @property int $term_time
 * @property int $time_to_cool
 * @property boolean $has_term
 * @property int $cooking_time
 * @property int $sort_order
 * @property boolean $status
 * @property-read  MenuItem $menuItems
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 */
class TechnicalCard extends Model
{
    use SoftDeletes;

    protected $table = 'technical_cards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'weight',
        'proteins',
        'fats',
        'carbohydrates',
        'calories',
        'composition',
        'composition_for_cook',
        'chef_comment',
        'cooking_type',
        'dish_type',
        'manufacturer_type',
        'term_time',
        'time_to_cool',
        'has_term',
        'cooking_time',
        'sort_order',
        'status',
    ];


    protected $casts = [
        'cooking_type'      => CookingType::class,
        'dish_type'         => DishType::class,
        'manufacturer_type' => ManufacturerType::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuItems()
    {
        return $this->hasMany(
            MenuItem::class,
            'technical_card_id',
            'id',
        );
    }
}
