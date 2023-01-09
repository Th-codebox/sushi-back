<?php

namespace App\Models\System;

use App\Enums\TransactionPaymentType;
use App\Enums\TransactionOperationType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionTransitType;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

/**
 * App\Models\Order\Order
 *
 * @property int $id
 * @property int $sender_id
 * @property int $order_id
 * @property int $operator_id
 * @property string $name
 * @property int $price
 * @property int $quantity_checks
 * @property int $balance_after
 * @property int $balance_before
 * @property TransactionTransitType $transit_type
 * @property TransactionPaymentType $payment_type
 * @property TransactionOperationType $operation_type
 * @property TransactionStatus $status
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Order $order
 * @property-read User $operator
 * @property-read User $sender
 *
 */
class Transaction extends Model
{

    use SoftDeletes;

    protected $table = 'transactions';


    public string $name;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'sender_id',
        'operator_id',
        'price',
        'transit_type',
        'payment_type',
        'operation_type',
        'quantity_checks',
        'status',
        'date',
        'archived_at',
        'balance_before',
        'balance_after',
        'filial_cash_box_id',
    ];
    protected $casts = [
        'transit_type'   => TransactionTransitType::class, // Example enum cast
        'payment_type'   => TransactionPaymentType::class, // Example enum cast
        'operation_type' => TransactionOperationType::class, // Example enum cast
        'status'         => TransactionStatus::class, // Example enum cast
        'date'           => 'date', // Example enum cast
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator()
    {
        return $this->belongsTo(User::class,'operator_id','id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }


    public function generateName(): void
    {

        if ($this->operation_type->is(TransactionOperationType::Receive)) {

            $this->name = "на сдачу";

        } elseif ($this->operation_type->is(TransactionOperationType::Send)) {

            if ((string)$this->payment_type === TransactionPaymentType::Cash) {
                $this->name = "сдал";
            } else {
                $this->name = $this->quantity_checks . " " . Lang::choice('чек|чека|чеков', $this->quantity_checks);
            }
        } elseif ($this->operation_type->is(TransactionOperationType::TopUpOrder)) {

            $this->name = "№ " . $this->order->code;
        }
    }
}
