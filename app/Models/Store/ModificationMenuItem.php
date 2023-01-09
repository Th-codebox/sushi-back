<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Category
 * @package App\Models\Store
 * @property  int $id
 * @property  int $menu_item_id
 * @property  int $modification_id
 * @property  float $price_rate
 * @property  int $price_add
 * @property  int $sort_order
 * @property  bool $status
 * @property  Carbon $created_at
 * @property  Carbon $updated_at
 * @property  Carbon $deleted_at
 * @property  int $actualPrice

 * @property  bool $activeModification
 * @property-read MenuItem $product
 * @property Modification $modification
 * @property TechnicalCard $actualTechnicalCard
 */
class ModificationMenuItem extends Model
{
    protected $table = 'modification_menu_item';

    public int $actualPrice = 0;
    public bool $activeModification = false;
    public ?TechnicalCard $actualTechnicalCard = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_item_id',
        'modification_id',
        'price_rate',
        'price_add',
        'sort_order',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menuItem() {
        return $this->belongsTo(MenuItem::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modification() {
        return $this->belongsTo(Modification::class)->with('technicalCard')->withTrashed();
    }
}
