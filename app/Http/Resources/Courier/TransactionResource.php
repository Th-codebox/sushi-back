<?php

namespace App\Http\Resources\Courier;

use App\Libraries\Helpers\MoneyHelper;
use App\Models\System\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TransactionResource
 * @package App\Http\Resources\Courier
 */
class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var Transaction $item
         */

        $item->generateName();
        date_default_timezone_set('UTC');
        return [
            'id'             => (int)$item->id,
            'name'           => (string)$item->name,
            'price'          => MoneyHelper::format($item->price),
            'paymentType'    => (string)$item->payment_type,
            'operationType'  => (string)$item->operation_type,
            'status'         => (string)$item->status,
            'quantityChecks' => (int)$item->quantity_checks,
            'balanceBefore'  =>  MoneyHelper::format($item->balance_before),
            'balanceAfter'   => MoneyHelper::format($item->balance_after),
            'date'           => $item->date ? $item->date->unix() :  Carbon::now()->unix(),
            //  'courierBalance' => (new CourierBalanceResource($this->whenLoaded('courierBalance'))),
        ];
    }
}
