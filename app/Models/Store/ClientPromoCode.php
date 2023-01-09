<?php

namespace App\Models\Store;

use App\Enums\PromoCodeAction;
use App\Models\Order\Order;
use App\Models\System\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class MenuItem
 *
 * @package App\Models\Store
 * @property int $id
 * @property int $client_id
 * @property int $quantity
 * @property int $promo_code_id
 * @property int $order_id
 * @property bool $activated
 * @property Carbon $activated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $dead_line
 * @property ?Carbon $date_begin
 * @property string|null $deleted_at
 *
 * @property Client $client
 * @property PromoCode $promoCode
 * @property Order $order
 * @mixin \Eloquent
 */
class ClientPromoCode extends Model
{
    use SoftDeletes;

    public int  $quantity = 0;
    protected $table = 'client_promo_codes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'promo_code_id',
        'order_id',
        'activated',
        'activated_at',
        'dead_line',
        'date_begin',
    ];

    protected $casts = [
        'type'        => PromoCodeAction::class, // Example enum cast
        'dead_line'   => 'date', // Example enum cast
        'date_begin'   => 'date', // Example enum cast
        'activate_at' => 'date', // Example enum cast
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }


}
