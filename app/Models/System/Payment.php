<?php

namespace App\Models\System;

use App\Enums\PaymentStatus;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Models\Order\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Class Payment
 * @package App\Models\System
 *
 * @property int $id
 * @property int $order_id
 * @property PaymentStatus $payment_status
 * @property string $payment_link
 * @property int $total
 * @property array $additional_info
 */
class Payment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'payment_status',
        'payment_link',
        'total',
        'additional_info',
    ];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isSuccess(): bool
    {
        return $this->payment_status->is(PaymentStatus::Success);
    }

    public function isCancel(): bool
    {
        return $this->payment_status->is(PaymentStatus::Cancel);
    }

    public function isWait(): bool
    {
        return $this->payment_status->is(PaymentStatus::Wait);
    }

    public function isRefund(): bool
    {
        return $this->payment_status->is(PaymentStatus::Refund);
    }

    public function checkStatus()
    {
        if ($this->isWait()) {

            /** @var PaymentGateway $paymentGateway */
            $paymentGateway = App::make(PaymentGateway::class);

            $status = $paymentGateway->getStatus($this->order);



            if ($status->isCompleted()) {

                $this->payment_status = PaymentStatus::Success();
                $this->save();

            } elseif ($status->isCancelled()) {

                $this->payment_status = PaymentStatus::Cancel();
                $this->save();

            }

        }


    }

    public function complete()
    {
        if ($this->isWait()) {
            $this->payment_status = PaymentStatus::Success();
            $this->save();
        }
    }

    public function cancel()
    {
        if ($this->isWait()) {
            $this->payment_status = PaymentStatus::Cancel();
            $this->save();
        }
    }

    public function getPaidSumInRub(): int
    {
        if ($this->isSuccess()) {
            return $this->total / 100;
        }
        return 0;
    }

    public function startRefund()
    {
        if ($this->isSuccess()) {
            $this->payment_status = PaymentStatus::WaitCancel;
            $this->save();
        }
    }

    public function refund()
    {
        if ($this->isSuccess()) {
            $this->payment_status = PaymentStatus::Refund;
            $this->save();
        }
    }




}
