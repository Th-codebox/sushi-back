<?php

namespace App\Models\Store;

use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Models\Order\Basket;
use App\Models\System\Client;
use App\Services\CRM\CRMServiceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class MenuItem
 *
 * @package App\Models\Store
 * @property int $id
 * @property int $referrer_client_id
 * @property int $sale_menu_item_id
 * @property int $sale_modification_menu_item_id
 * @property string $name
 * @property string $description
 * @property string $code
 * @property string $action
 * @property string $type
 * @property int $sale_subtraction
 * @property int $sale_percent
 * @property bool $show_menu
 * @property bool $only_crm
 * @property bool $status
 * @property bool $only_first_order
 * @property bool $repeat
 * @property int $days_for_active
 * @property int $sort_order
 * @property int $day_of_week_begin
 * @property int $day_of_week_end
 * @property int $min_total_price
 * @property-read  Category $categories
 * @property Carbon $time_available_from
 * @property Carbon $time_on
 * @property Carbon $time_available_to
 * @property Carbon $time_end
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @property Client $referrerClient
 * @property MenuItem $saleMenuItem
 * @property ModificationMenuItem $saleModificationMenuItemId
 * @mixin \Eloquent
 */
class PromoCode extends Model
{
    use SoftDeletes;

    protected $table = 'promo_codes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'code',
        'type',
        'sale_percent',
        'sale_subtraction',
        'referrer_client_id',
        'sale_menu_item_id',
        'sale_modification_menu_item_id',
        'time_on',
        'time_end',
        'show_menu',
        'status',
        'sort_order',
        'only_first_order',
        'action',
        'days_for_active',
        'only_crm',
        'repeat',
        'time_available_from',
        'time_available_to',
        'day_of_week_begin',
        'day_of_week_end',
        'min_total_price'
    ];

    protected $casts = [
        'action'              => PromoCodeAction::class, // Example enum cast
        'type'                => PromoCodeType::class, // Example enum cast
        'time_on'             => 'datetime',
        'time_end'            => 'datetime',
        'time_available_from' => 'datetime:H:i',
        'time_available_to'   => 'datetime:H:i',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function saleMenuItem()
    {
        return $this->hasOne(MenuItem::class, 'id', 'sale_menu_item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function saleModificationMenuItem()
    {
        return $this->hasOne(ModificationMenuItem::class, 'id', 'sale_modification_menu_item_id')->with('modification');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function referrerClient()
    {
        return $this->hasOne(Client::class, 'id', 'referrer_client_id');
    }

    public function getMinTotalPriceFormatted(): string
    {
        return $this->min_total_price / 100 . "руб.";
    }

    /**
     * Проверяем возможность применения промокода к корзине
     * @param Basket $basket
     * @throws CRMServiceException
     */
    public function checkCanApplyToBasket(Basket $basket)
    {
        if ($this->min_total_price && $this->min_total_price > $basket->total_price) {
            throw new CRMServiceException("Невозможно применить промокод! Минимальная сумма заказа {$this->getMinTotalPriceFormatted()}");
        }

    }

}
