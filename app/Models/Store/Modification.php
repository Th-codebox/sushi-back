<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Enums\{ModificationAction,ModificationType};

/**
 * Class Category
 * @package App\Models\Store
 * @property  int $id
 * @property  int $technical_card_id
 * @property  string $name
 * @property  ModificationAction $action
 * @property  string $description
 * @property  float $price_rate
 * @property  int $price_add
 * @property  ModificationType $type
 * @property  string $name_on
 * @property  string $name_off
 * @property  int $sort_order
 * @property  bool $status
 * @property  Carbon $created_at
 * @property  Carbon $updated_at
 * @property  Carbon $deleted_at
 *
 * @property TechnicalCard $technicalCard
 *
 */
class Modification extends Model
{
    use SoftDeletes;

    protected $casts = [
        'action'      => ModificationAction::class,
        'type' => ModificationType::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'technical_card_id',
        'name',
        'action',
        'description',
        'price_rate',
        'price_add',
        'type',
        'name_on',
        'name_off',
        'sort_order',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modificationMenuItem() {
        return $this->hasMany(ModificationMenuItem::class,'modification_id','id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function technicalCard() {
        return $this->hasOne(TechnicalCard::class,'id','technical_card_id')->withTrashed();
    }
}
