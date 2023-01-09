<?php

namespace App\Models\Store;

use App\Enums\MenuItemType;
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
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property int $souse_id
 * @property int $bonus_add
 * @property int $price
 * @property int $old_price
 * @property MenuItemType $type
 * @property string $image
 * @property string $composition
 * @property string $sticker_type
 * @property string $sticker_marketing
 * @property bool $status
 * @property bool $has_stop
 * @property bool $need_person_count
 * @property bool $hide
 * @property int $sort_order
 * @property int $existInBasket
 * @property string $h1
 * @property string $meta_title
 * @property string $meta_description
 * @property int $technical_card_id
 * @property-read  Category $categories
 * @property-read  Collection $collections
 * @property  ModificationMenuItem $modifications
 * @property  TechnicalCard $technicalCard
 * @property  TechnicalCard $actualTechnicalCard
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read int|null $collections_count
 * @property MenuBundleItem $bundleItems
 * @property MenuItem $souse
 */
class MenuItem extends Model
{
    use SoftDeletes;

    public bool $existInBasket = false;
    protected $table = 'menu_items';
    public ?TechnicalCard $actualTechnicalCard = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'bonus_add',
        'sticker_type',
        'sticker_marketing',
        'has_stop',
        'image',
        'composition',
        'status',
        'type',
        'sort_order',
        'technical_card_id',
        'souse_id',
        'h1',
        'meta_title',
        'meta_description',
        'need_person_count',
        'hide',
    ];

    protected $casts = [
        'sticker_type'      => StickerType::class, // Example enum cast
        'sticker_marketing' => StickerMarketing::class, // Example enum cast
        'type'              => MenuItemType::class, // Example enum cast
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technicalCard()
    {
        return $this->belongsTo(TechnicalCard::class)->withTrashed();

    }

    /**
     * @return mixed
     */
    public function souse()
    {
        return $this->hasOne(__CLASS__, 'id', 'souse_id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'menu_item_to_category',
            'menu_item_id',
            'category_id',
            'id',
            'id'
        );

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bundleItems()
    {
        return $this->hasMany(
            MenuBundleItem::class,
            'menu_item_bundle_id',
            'id',
        )->with(['menuItem', 'modificationMenuItem']);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        return $this->belongsToMany(
            Collection::class,
            'menu_item_to_collection',
            'menu_item_id',
            'collection_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modifications()
    {
        return $this->hasMany(
            ModificationMenuItem::class,
            'menu_item_id',
            'id',
        )->with('modification')->with('modification.technicalCard');
    }


    public function calcModifiedPrice(): void
    {
        $this->modifications->map(function (ModificationMenuItem $modificationMenuItem) {

            $priceRate = $modificationMenuItem->price_rate ?: $modificationMenuItem->modification->price_rate;
            $priceAdd = $modificationMenuItem->price_add ?: $modificationMenuItem->modification->price_add;

            $modificationMenuItem->actualPrice = ($this->price * $priceRate) + $priceAdd;
        });
    }

    public function calcModifiedTechCard(bool $techCardSet = true): void
    {
        $setCheck = false;

        $this->categories->map(function (Category $category) use (&$setCheck) {
            if ($category->name === 'Сеты') {
                $setCheck = true;
            }
        });

        if ((!$setCheck || $techCardSet) && $this->bundleItems->toArray()) {


            $this->actualTechnicalCard = new TechnicalCard();


            $this->bundleItems->map(function (MenuBundleItem $menuBundleItem) {
                $menuBundleItem->withTrashed();
                $weight = $menuBundleItem->menuItem->technicalCard->weight;
                $calories = $menuBundleItem->menuItem->technicalCard->calories;
                $carbohydrates = $menuBundleItem->menuItem->technicalCard->carbohydrates;
                $proteins = $menuBundleItem->menuItem->technicalCard->proteins;
                $fats = $menuBundleItem->menuItem->technicalCard->fats;

                if ($menuBundleItem->modificationMenuItem) {
                    if ($menuBundleItem->modificationMenuItem->modification->action->value === ModificationAction::Add && $menuBundleItem->modificationMenuItem->modification->technicalCard) {

                        $weight += $menuBundleItem->modificationMenuItem->modification->technicalCard->weight;
                        $calories += $menuBundleItem->modificationMenuItem->modification->technicalCard->calories;
                        $carbohydrates += $menuBundleItem->modificationMenuItem->modification->technicalCard->carbohydrates;
                        $proteins += $menuBundleItem->modificationMenuItem->modification->technicalCard->proteins;
                        $fats += $menuBundleItem->modificationMenuItem->modification->technicalCard->fats;

                    }

                    if ($menuBundleItem->modificationMenuItem->modification->action->value === ModificationAction::Subtract) {

                        $weight /= 2;
                        $calories /= 2;
                        $carbohydrates /= 2;
                        $proteins /= 2;
                        $fats /= 2;
                    }
                }


                $this->actualTechnicalCard->weight += $weight;
                $this->actualTechnicalCard->calories += $calories;
                $this->actualTechnicalCard->carbohydrates += $carbohydrates;
                $this->actualTechnicalCard->proteins += $proteins;
                $this->actualTechnicalCard->fats += $fats;

            });
        } else {

            $this->modifications->map(function (ModificationMenuItem $modificationMenuItem) {


                $modificationMenuItem->actualTechnicalCard = new TechnicalCard();


                if ($modificationMenuItem->modification->action->value === ModificationAction::Add) {

                    $modificationMenuItem->actualTechnicalCard->weight = $this->technicalCard->weight + $modificationMenuItem->modification->technicalCard->weight;
                    $modificationMenuItem->actualTechnicalCard->calories = $this->technicalCard->calories + $modificationMenuItem->modification->technicalCard->calories;
                    $modificationMenuItem->actualTechnicalCard->carbohydrates = $this->technicalCard->carbohydrates + $modificationMenuItem->modification->technicalCard->carbohydrates;
                    $modificationMenuItem->actualTechnicalCard->proteins = $this->technicalCard->proteins + $modificationMenuItem->modification->technicalCard->proteins;
                    $modificationMenuItem->actualTechnicalCard->fats = $this->technicalCard->fats + $modificationMenuItem->modification->technicalCard->fats;

                }

                if ($modificationMenuItem->modification->action->value === ModificationAction::Subtract) {

                    $modificationMenuItem->actualTechnicalCard->weight = $this->technicalCard->weight / 2;
                    $modificationMenuItem->actualTechnicalCard->calories = $this->technicalCard->calories / 2;
                    $modificationMenuItem->actualTechnicalCard->carbohydrates = $this->technicalCard->carbohydrates / 2;
                    $modificationMenuItem->actualTechnicalCard->proteins = $this->technicalCard->proteins / 2;
                    $modificationMenuItem->actualTechnicalCard->fats = $this->technicalCard->fats / 2;
                }

            });

        }
    }
}
