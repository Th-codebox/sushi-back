<?php

namespace App\Models\Store;

use App\Enums\CollectionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Collection
 *
 * @package App\Models\Store
 * @property int $id
 * @property string $name
 * @property string $target
 * @property string $slug
 * @property string $ico
 * @property string $sub_title
 * @property string $image
 * @property int $price
 * @property string $description
 * @property bool $status
 * @property int $sort_order
 * @property-read Category $category
 * @property-read MenuItem $menuItems
 * @property-read CollectionType $types
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $category_id
 * @property string|null $deleted_at
 * @property-read int|null $menuItems_count
 * @mixin \Eloquent
 */
class Collection extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sub_title',
        'slug',
        'ico',
        'type',
        'target',
        'description',
        'price',
        'status',
        'sort_order',
        'category_id'
    ];


    public function types() {
        return $this->hasMany(\App\Models\Store\CollectionType::class,'collection_id','id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function menuItems() {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_to_collection',
            'collection_id',
            'menu_item_id',
            'id',
            'id'
        );
    }
}
