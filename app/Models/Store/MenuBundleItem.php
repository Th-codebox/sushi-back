<?php

namespace App\Models\Store;

use App\Enums\ModificationAction;
use App\Enums\StickerMarketing;
use App\Enums\StickerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class MenuItem
 *
 * @package App\Models\Store
 * @property int $id
 * @property int $menu_item_id
 * @property int $menu_item_bundle_id
 * @property int $modification_menu_item_id
 * @property-read  ModificationMenuItem $modificationMenuItem
 * @property-read  MenuItem $menuItem
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MenuBundleItem extends Model
{
    use SoftDeletes;

    protected $table = 'menu_bundle_items';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_item_id',
        'menu_item_bundle_id',
        'modification_menu_item_id',
    ];




    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function menuItem()
    {
        return $this->hasOne(
            MenuItem::class,
            'id',
            'menu_item_id',
        )->with(['modifications'])->with('technicalCard')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modificationMenuItem()
    {
        return $this->belongsTo(
            ModificationMenuItem::class,
            'modification_menu_item_id',
            'id',
        )->with('modification')->with('modification.technicalCard');
    }
}
