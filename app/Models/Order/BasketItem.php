<?php

namespace App\Models\Order;

use App\Enums\BasketItemType;
use App\Enums\MenuItemType;
use App\Enums\PromoCodeAction;
use App\Models\Domain\Store\SingleMenuItem;
use App\Models\Domain\Store\SingleMenuItemCollection;
use Illuminate\Support\Collection;
use App\Models\Store\{MenuBundleItem, MenuItem, ModificationMenuItem, TechnicalCard};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class BasketItem
 * @property int $id
 * @property int $basket_id
 * @property int $modification_menu_item_id
 * @property int $menu_item_id
 * @property int $sub_menu_item_id
 * @property BasketItemType $type
 * @property int $price
 * @property boolean $free
 * @property string $comment
 * @property string $fullName
 * @property string $idsString
 * @property int $quantity
 * @property int $weight
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property int $total
 *
 * @property Basket $basket
 * @property MenuItem $menuItem
 * @property MenuItem $subMenuItem
 * @property ModificationMenuItem $modificationMenuItem
 *
 * @property TechnicalCard $technicalCard
 *
 * @package App\Models\Order
 */
class BasketItem extends Model
{

    public ?TechnicalCard $technicalCard = null;

    public int $quantity = 0;
    public int $weight = 0;
    public string $fullName = '';
    public string $idsString = '';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basket_id',
        'modification_menu_item_id',
        'menu_item_id',
        'sub_menu_item_id',
        'type',
        'price',
        'comment',
        'free',
    ];

    protected $casts = [
        'type' => BasketItemType::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|mixed
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function menuItem()
    {
        return $this->hasOne(MenuItem::class, 'id', 'menu_item_id')->withTrashed()->with('technicalCard')->with('bundleItems')->with('souse');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subMenuItem()
    {
        return $this->hasOne(MenuItem::class, 'id', 'sub_menu_item_id')->withTrashed();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function modificationMenuItem()
    {
        return $this->hasOne(ModificationMenuItem::class, 'id', 'modification_menu_item_id')->with('modification');
    }


    public function selectActiveModification(): void
    {
        $this->menuItem->modifications->map(function (ModificationMenuItem $modificationMenuItem) {
            if ($modificationMenuItem->id === $this->modification_menu_item_id) {
                $modificationMenuItem->activeModification = true;

            }
            return $modificationMenuItem;
        });
    }


    public function actualTechCard(): void
    {

        if ($this->type->is(BasketItemType::Construct)) {
            $this->technicalCard = new TechnicalCard();
            $this->technicalCard->weight = $this->menuItem->technicalCard->weight + $this->subMenuItem->technicalCard->weight;
            $this->technicalCard->calories = $this->menuItem->technicalCard->calories + $this->subMenuItem->technicalCard->calories;
            $this->technicalCard->carbohydrates = $this->menuItem->technicalCard->carbohydrates + $this->subMenuItem->technicalCard->carbohydrates;
            $this->technicalCard->proteins = $this->menuItem->technicalCard->proteins + $this->subMenuItem->technicalCard->proteins;
            $this->technicalCard->fats = $this->menuItem->technicalCard->fats + $this->subMenuItem->technicalCard->weight;
        }
    }


    public function calcTotalPrice(): void
    {

        $priceRate = 1;
        $priceAdd = 0;

        if ($this->type->is(BasketItemType::Construct)) {

            if ($this->modificationMenuItem && $this->modificationMenuItem->menu_item_id === $this->menuItem->id && $this->modificationMenuItem->menu_item_id === $this->subMenuItem->id) {
                $priceRate = $this->modificationMenuItem->price_rate ?: $this->modificationMenuItem->modification->price_rate;
                $priceAdd = $this->modificationMenuItem->price_add ?: $this->modificationMenuItem->modification->price_add;
            }

            $actualPrice = ((int)$this->menuItem->price + (int)$this->subMenuItem->price) / 2;

        } else {

            if ($this->modificationMenuItem && $this->modificationMenuItem->menu_item_id === $this->menuItem->id) {

                $priceRate = $this->modificationMenuItem->price_rate ?: $this->modificationMenuItem->modification->price_rate;
                $priceAdd = $this->modificationMenuItem->price_add ?: $this->modificationMenuItem->modification->price_add;
            }

            $actualPrice = (int)$this->menuItem->price;
        }

        if ($this->free && ($this->basket->clientPromoCode && (string)$this->basket->clientPromoCode->promoCode->action === PromoCodeAction::DishGift)) {
            $this->price = 0;
        } else {
            $this->price = ($actualPrice * (float)$priceRate) + $priceAdd;
        }


    }

    public function getFullName(): string
    {

        if ((string)$this->type === BasketItemType::Construct) {
            $subItem = 'Пицца из половинок (' . $this->menuItem->name . '-' . $this->subMenuItem->name . ')';
        }

        $comment = $this->comment ? ', ' . $this->comment : ' ';

        $modification = '';

        if ($this->modificationMenuItem) {
            $modification = ' + ' . $this->modificationMenuItem->modification->name;
        }

        return $subItem ?? $this->menuItem->name . $modification . $comment;

    }

    public function getSingleMenuItems(): SingleMenuItemCollection
    {
        #TODO Конструктор пиц

        /* Набор */
        if ($this->menuItem->type->is(MenuItemType::Bundle())) {

            return $this->menuItem->bundleItems->reduce(
                function (SingleMenuItemCollection $collection, MenuBundleItem $item) {
                $singleMenuItem = new SingleMenuItem(
                    $this->id,
                    $item->menuItem,
                    $item->modificationMenuItem
                );

                return $collection->push($singleMenuItem);
            }, new SingleMenuItemCollection());
        }

        /* Обычное блюдо */

        $singleMenuItem = new SingleMenuItem(
            $this->id,
            $this->menuItem,
            $this->modificationMenuItem
        );

        return new SingleMenuItemCollection([ $singleMenuItem ]);

    }
}
