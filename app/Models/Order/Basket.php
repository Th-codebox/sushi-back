<?php

namespace App\Models\Order;

use App\Enums\BasketSource;
use App\Enums\BasketStatus;
use App\Enums\CheckType;
use App\Enums\CookingType;
use App\Enums\DeliveryType;
use App\Enums\ManufacturerType;
use App\Enums\MenuItemType;
use App\Enums\PaymentType;
use App\Libraries\DTO\BasketGroupItem;
use App\Models\Store\Category;
use App\Models\Store\ClientPromoCode;
use App\Models\Store\Filial;
use App\Models\Store\MenuBundleItem;
use App\Models\Store\ModificationMenuItem;
use App\Models\Store\PromoCode;
use App\Services\CRM\CRMServiceException;
use App\Models\System\{Call, Client, ClientAddress, Utm};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Basket
 * @property int $id
 * @property int $filial_id
 * @property int $client_id
 * @property int $client_address_id
 * @property int $client_promo_code_id
 * @property int $cooking_and_delivery_time
 * @property string $uuid
 * @property string $delivery_type
 * @property PaymentType $payment_type
 * @property BasketSource $basket_source
 * @property string $comment
 * @property array $additional_info
 * @property int $total_price
 * @property int $delivery_price
 * @property int $discount_amount
 * @property int $free_delivery
 * @property int $client_money
 * @property Carbon $date_delivery
 * @property Carbon $time_delivery
 * @property int $time_in_delivery
 * @property int $persons
 * @property string $user_agent
 * @property string $client_source
 * @property string $comment_for_courier
 * @property string $payment_phone
 * @property boolean $no_call
 * @property boolean $to_datetime
 * @property boolean $needPersonsCount
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property-read Client $client
 * @property-read ClientAddress $client_address
 * @property-read ClientPromoCode $clientPromoCode
 * @property-read Utm $utm
 * @property-read BasketItem $items
 * @property-read Collection $groupItems
 * @property-read Order $order
 * @property-read Filial $filial
 * @property-read Call $call
 *
 * @package App\Models\Basket
 */
class Basket extends Model
{
    use SoftDeletes;

    public string $paymentLink = '';
    public bool $needPersonsCount;
    public Collection $groupItems;

    protected $casts = [
        'payment_type'  => PaymentType::class, // Example enum cast
        'delivery_type' => DeliveryType::class, // Example enum cast
        'basket_source' => BasketSource::class, // Example enum cast
        'status'        => BasketStatus::class, // Example enum cast
        'date_delivery' => 'datetime',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_promo_code_id',
        'cooking_and_delivery_time',
        'previous_client_address_id',
        'previous_filial_id',
        'filial_id',
        'client_id',
        'client_address_id',
        'utm_id',
        'delivery_type',
        'payment_type',
        'total_price',
        'delivery_price',
        'free_delivery',
        'persons',
        'comment',
        'comment_for_courier',
        'user_agent',
        'ip',
        'recipient_name',
        'recipient_phone',
        'earned_point',
        'client_money',
        'date_delivery',
        'time_delivery',
        'time_in_delivery',
        'no_call',
        'client_source',
        'basket_source',
        'status',
        'to_datetime',
        'call_id',
        'payment_phone',
        'call_phone',
        'discount_amount',
        'uuid',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientAddress()
    {
        return $this->hasOne(ClientAddress::class, 'id', 'client_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function call()
    {
        return $this->hasOne(Call::class, 'id', 'call_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientPromoCode()
    {
        return $this->hasOne(ClientPromoCode::class, 'id', 'client_promo_code_id')->with('promoCode');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function utm()
    {
        return $this->hasOne(Utm::class, 'id', 'utm_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function filial()
    {
        return $this->hasOne(Filial::class, 'id', 'filial_id');
    }

    public function items()
    {
        return $this->hasMany(BasketItem::class, 'basket_id', 'id')->with(['menuItem', 'modificationMenuItem']);
    }

    /**
     * @param string $type
     * @return Collection
     */
    public function groupItems(string $type = CheckType::Main): Collection
    {
        $groupItems = [];


        $this->items->map(function (BasketItem $basketItem) use (&$groupItems, $type) {


            if ($type === CheckType::Cold || $type === CheckType::Hot) {

                /**
                 * @TODO УБРАСТЬ КОСТЫЛЬ  СЕТЫ сет СЕТ
                 */
                $setCheck = false;

                $basketItem->menuItem->categories->map(function (Category $category) use (&$setCheck) {
                    if ($category->name === 'Сеты') {
                        $setCheck = true;
                    }
                });

                if (!$setCheck && $basketItem->menuItem->type->is(MenuItemType::Bundle)) {

                    $basketItem->menuItem->bundleItems->map(function (MenuBundleItem $menuBundleItem) use (&$groupItems, $basketItem, $type) {

                        if (($type === CheckType::Cold && (string)$menuBundleItem->menuItem->technicalCard->manufacturer_type === ManufacturerType::Cold)
                            || ($type === CheckType::Hot && (string)$menuBundleItem->menuItem->technicalCard->manufacturer_type === ManufacturerType::Hot)) {

                            $uniqNameBundleItem = trim($menuBundleItem->menu_item_id . '-' . $menuBundleItem->modification_menu_item_id . '-' . $menuBundleItem->sub_menu_item_id, '-');

                            $basketModel = new BasketItem();
                            $basketModel->modificationMenuItem = $menuBundleItem->modificationMenuItem;
                            $basketModel->menuItem = $menuBundleItem->menuItem;
                            $basketModel->type = $basketItem->type;
                            $basketModel->basket_id = $basketItem->basket_id;
                            $basketModel->comment = $basketItem->comment;
                            $basketModel->menu_item_id = $menuBundleItem->menuItem->id;
                            $basketModel->modification_menu_item_id = $menuBundleItem->modificationMenuItem->id ?? null;


                            if (array_key_exists($uniqNameBundleItem, $groupItems) && $groupItems[$uniqNameBundleItem] instanceof BasketItem) {
                                $groupItems[$uniqNameBundleItem]->quantity++;
                            } else {
                                $groupItems[$uniqNameBundleItem] = $basketModel;
                                $groupItems[$uniqNameBundleItem]->fullName = $basketModel->getFullName();
                                $groupItems[$uniqNameBundleItem]->quantity = 1;
                            }
                        }
                    });
                } else {

                    if (($type === CheckType::Cold && (string)$basketItem->menuItem->technicalCard->manufacturer_type === ManufacturerType::Cold)
                        || ($type === CheckType::Hot && (string)$basketItem->menuItem->technicalCard->manufacturer_type === ManufacturerType::Hot)) {

                        $uniqName = trim($basketItem->menu_item_id . '-' . $basketItem->modification_menu_item_id . '-' . $basketItem->sub_menu_item_id, '-');

                        if (array_key_exists($uniqName, $groupItems)) {
                            $groupItems[$uniqName]->quantity++;
                        } else {
                            $groupItems[$uniqName] = $basketItem;
                            $groupItems[$uniqName]->fullName = $basketItem->getFullName();
                            $groupItems[$uniqName]->quantity = 1;
                        }
                    }
                }

            }

            if ($type === CheckType::Main) {
                $basketItem->menuItem->calcModifiedPrice();
                $basketItem->menuItem->calcModifiedTechCard(false);

                $basketItem->calcTotalPrice();
                $basketItem->selectActiveModification();


                $uniqName = $basketItem->menu_item_id . '-' . $basketItem->modification_menu_item_id . '-' . $basketItem->sub_menu_item_id;

                if (array_key_exists($uniqName, $groupItems)) {
                    $groupItems[$uniqName]->quantity++;
                    $groupItems[$uniqName]->price += $basketItem->price;
                    $groupItems[$uniqName]->idsString .= '-' . $basketItem['id'];
                } else {
                    $groupItems[$uniqName] = $basketItem;
                    $groupItems[$uniqName]->fullName = $basketItem->getFullName();
                    $groupItems[$uniqName]->quantity = 1;
                    $groupItems[$uniqName]->idsString = $basketItem['id'];

                    $weight = $basketItem->menuItem->technicalCard->weight ?? 0;

                    $basketItem->menuItem->modifications->map(function (ModificationMenuItem $modificationMenuItem) use (&$weight) {
                        if ($modificationMenuItem->activeModification) {
                            $weight = $modificationMenuItem->actualTechnicalCard->weight;
                        }
                    });

                    $groupItems[$uniqName]->weight = $weight;
                }


                if ($souse = $basketItem->menuItem->souse) {
                    $souseUniqName = 'souse' . '-' . $souse->id;

                    if (array_key_exists($souseUniqName, $groupItems) && $groupItems[$uniqName] instanceof BasketItem) {
                        $groupItems[$uniqName]->quantity++;
                    } else {
                        $groupItems[$uniqName]->fullName = $souse->name;
                        $groupItems[$uniqName] = $souse;
                        $groupItems[$uniqName]->price = 0;
                        $groupItems[$uniqName]->quantity = 1;
                        $groupItems[$uniqName]->weight = $souse->technicalCard->weight ?? 0;
                    }
                }

                if ($basketItem['need_persons_count']) {
                    $this->needPersonsCount = true;
                }
            }
        });

        $this->groupItems = $this->newCollection();

        foreach ($groupItems as $groupItem) {
            $this->groupItems->push(new BasketGroupItem($groupItem));
        }

        if ($type === CheckType::Cold) {
            $this->groupItems = $this->groupItems->sortBy(function (BasketGroupItem $basketGroupItem) {


                if ($basketGroupItem->menuItem && $basketGroupItem->menuItem->technicalCard) {
                    if ((string)$basketGroupItem->menuItem->technicalCard->cooking_type === CookingType::Baked) {
                        return -1;
                    } elseif ((string)$basketGroupItem->menuItem->technicalCard->cooking_type === CookingType::Fried) {
                        return 0;
                    } elseif ((string)$basketGroupItem->menuItem->technicalCard->cooking_type === CookingType::Cold) {
                        return 1;
                    }
                }
                return 1;
            });
        }
        return $this->groupItems->values();

    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'basket_id', 'id');
    }


    public function timeToCompleteOrder(): ?int
    {
        $timeInDelivery = $this->cooking_and_delivery_time ?: $this->time_in_delivery;

        /*   $this->items->map(function (BasketItem $item) use (&$completeTime) {

              if ($item->menuItem->type->is(MenuItemType::Bundle)) {

                  $item->menuItem->bundleItems->map(function (MenuBundleItem $bundleItem) use (&$completeTime) {

                      $completeTime += $bundleItem->menuItem->technicalCard->cooking_time;
                  });
              } else {
                  $completeTime += $item->menuItem->technicalCard->cooking_time;
              }
          });*/

        return (int)$timeInDelivery;
    }

    public function isSourceAggregator(): bool
    {
        return in_array($this->basket_source, [
            BasketSource::Yandex,
            BasketSource::DeliveryClub,
        ]);
    }
}
