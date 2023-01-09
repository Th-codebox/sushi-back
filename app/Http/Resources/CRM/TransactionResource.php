<?php

namespace App\Http\Resources\CRM;

use App\Models\System\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TransactionResource
 * @package App\Http\Resources\CRM
 */
class TransactionResource extends JsonResource
{
    /**
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

        return [
            'id'             => $item->id,
            'name'           => $item->name,
            'price'          => $item->price,
            'paymentType'    => $item->payment_type,
            'operationType'  => $item->operation_type,
            'status'         => $item->status,
            'quantityChecks' => $item->quantity_checks,
            'date'           => $item->date ? $item->date->unix() : null,
            'sender'         => (new UserResource($this->whenLoaded('sender'))),
        ];
    }
}
