<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Category
 *
 * @package App\Models\Store
 * @property int $id
 * @property string $name
 * @property string $ico
 * @property string $slug
 * @property string $description
 * @property string $h1
 * @property string $meta_title
 * @property string $meta_description
 * @property bool $status
 * @property int $sort_order
 * @property int $crm_sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read MenuItem $menuItems
 * @property-read MenuItem $collections
 * @property string|null $deleted_at
 * @property-read int|null $collections_count
 * @property-read int|null $menuItems_count
 * @mixin \Eloquent
 */
class Category extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'ico',
        'description',
        'status',
        'sort_order',
        'crm_sort_order',
        'h1',
        'meta_title',
        'meta_description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menuItems()
    {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_to_category',
            'category_id',
            'menu_item_id',
            'id',
            'id'
        )
            ->with([ 'modifications', 'bundleItems', 'technicalCard' ])
            ->orderBy('name', 'asc')
            ->where('status', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collections()
    {
        return $this->hasMany(Collection::class, 'category_id', 'id');
    }

}
