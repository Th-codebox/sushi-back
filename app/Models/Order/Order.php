<?php

namespace App\Models\Order;

use App\Enums\BasketItemType;
use App\Enums\CheckType;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Store\Filial;
use App\Models\Domain\Store\SingleMenuItemCollection;
use App\Models\System\{Call, Client, ClientAddress, Payment, User, Utm};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\System\Activity;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Order
 * @property int $id
 * @property int $client_address_id
 * @property int $basket_id
 * @property int $filial_id
 * @property int $client_id
 * @property int $courier_id
 * @property int $manager_id
 * @property int $cooking_and_delivery_time
 * @property string $promo_code
 * @property string $delivery_type
 * @property PaymentType $payment_type
 * @property string $code
 * @property string $kitchen_cell
 * @property string $courier_cell
 * @property OrderStatus $order_status
 * @property array $additional_info
 * @property int $total_price
 * @property int $delivery_price
 * @property int $discount_amount
 * @property bool $is_lateness
 * @property bool $to_datetime
 * @property bool $canceled_confirm_by_courier
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property string|null $client_money
 * @property int|Carbon $dead_line
 * @property int|Carbon $date
 * @property int|Carbon $completed_at
 * @property int|Carbon $start_at
 * @property int|Carbon $completedAt
 * @property int|Carbon $canceledAt
 * @property-read Basket $basket
 * @property-read Client $client
 * @property-read User $courier
 * @property-read User $manager
 * @property-read ClientAddress $clientAddress
 * @property-read Utm $utm
 * @property-read Payment $payment
 *
 * @package App\Models\Order
 */
class Order extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['order_status'];
    protected static $logOnlyDirty = true;

    public ?Carbon $completedAt = null;
    public ?Carbon $canceledAt = null;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filial_id',
        'basket_id',
        'client_id',
        'manager_id',
        'courier_id',
        'client_address_id',
        'cooking_and_delivery_time',
        'code',
        'promo_code',
        'delivery_type',
        'payment_type',
        'total_price',
        'delivery_price',
        'kitchen_cell',
        'courier_cell',
        'order_status',
        'additional_info',
        "client_money",
        "completed_at",
        "start_at",
        "dead_line",
        "date",
        "to_datetime",
        "canceled_confirm_by_courier",
        "discount_amount",
        "is_lateness",
    ];


    protected $casts = [
        'order_status' => OrderStatus::class,
        'payment_type' => PaymentType::class,
        'dead_line'    => 'datetime',
        'date'         => 'date',
        'completed_at' => 'datetime',
        'start_at'     => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function basket()
    {
        return $this->hasOne(Basket::class, 'id', 'basket_id')->with('clientPromoCode')->with('clientAddress');
    }


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
    public function courier()
    {
        return $this->hasOne(User::class, 'id', 'courier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function filial()
    {
        return $this->hasOne(Filial::class, 'id', 'filial_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientAddress()
    {
        return $this->hasOne(ClientAddress::class, 'id', 'client_address_id');
    }


    public function travelTime()
    {
        if ($this->start_at && $this->completed_at) {
            return $this->completed_at->unix() - $this->start_at->unix();
        }
        return null;

    }

    /**
     * @return bool|null
     */
    public function hasLateness(): ?bool
    {
        if ($this->dead_line && $this->completed_at) {
            return $this->dead_line->unix() < $this->completed_at->unix();
        }
        return null;
    }

    public function diffTimeDelivery(): ?int
    {
        if ($this->dead_line && $this->completed_at) {
            return $this->dead_line->unix() - $this->completed_at->unix();
        }
        return null;
    }

    public function isLateness(): bool
    {
        return (bool)$this->is_lateness;
    }
    public function isToday(): bool
    {
        return $this->dead_line && !$this->completed_at && (Carbon::now()->unix() - $this->dead_line->unix()) > 0;
    }

    /**
     * @return string
     */
    public function getFullBasketItemsName(): string
    {
        $nameQuantityItems = [];

        $fullBasketName = '';

        $this->basket->items->map(function (BasketItem $item) use (&$nameQuantityItems) {

            $uniqueName = $item->getFullName();

            if (array_key_exists($uniqueName, $nameQuantityItems)) {
                $nameQuantityItems[$uniqueName]++;
            } else {
                $nameQuantityItems[$uniqueName] = 1;
            }


        });

        foreach ($nameQuantityItems as $name => $quantity) {
            $fullBasketName .= $name . ' x' . $quantity . ' / ';
        }

        return trim($fullBasketName, '/ ');
    }

    public function info(): array
    {
        //$activityLogs = $this->activities()->where('description','!=','order_utm')->get();
        $activityLogs = $this->activities->reject(function (Activity $item) {
            return $item->description == 'order_utm';
        });

        $info = [];

        foreach ($activityLogs as $key => $activityLog) {
            /**
             * @var Activity $activityLog
             */
            if (isset($activityLog['properties']['attributes']['order_status'])) {

                if ($activityLog['properties']['attributes']['order_status'] === OrderStatus::Completed) {
                    $this->completedAt = $activityLog['created_at'];
                }
                if ($activityLog['properties']['attributes']['order_status'] === OrderStatus::Canceled) {
                    $this->canceledAt = $activityLog['created_at'];
                }

                $nextCreateAt = $activityLogs[$key + 1]['created_at'] ?? $activityLog['created_at'];

                $info['changeStatuses'][] = [
                    'status'             => $activityLog['properties']['attributes']['order_status'] ?? null,
                    'time'               => $activityLog['created_at']->format('H:i'),
                    'diffTimePrevStatus' =>  $activityLog['created_at']->diffInSeconds($nextCreateAt),
                ];
            }
        }

        $info['fullName'] = $this->getFullBasketItemsName();

        return $info;
    }

    public function getTotalPriceInRub()
    {
        return $this->total_price / 100;
    }

    public function getDiscountPercent()
    {
        if ($this->basket->clientPromoCode) {
            return $this->basket->clientPromoCode->promoCode->sale_percent;
        }
    }

    /**
     * Возвращает оплачен ли заказ Онлайн
     * @return bool
     */
    public function isPaidOnline(): bool
    {
        if ($this->payment && $this->payment->isSuccess()) {
            return true;
        }
        return false;
    }

    public function getAllSingleMenuItems(): SingleMenuItemCollection
    {
        return $this->basket->items->reduce(function (SingleMenuItemCollection $collection, BasketItem $basketItem) {
            return $collection->concat($basketItem->getSingleMenuItems());
        }, new SingleMenuItemCollection());
    }
}
