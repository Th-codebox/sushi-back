<?php

namespace App\Http\Resources\Courier;

use App\Libraries\Helpers\MoneyHelper;
use App\Models\System\Courier;
use App\Models\System\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CourierBalanceResource
 * @package App\Http\Resources\Courier
 */
class CourierBalanceResource extends JsonResource
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
         * @var User $item
         */
        return [
            'cashBalance'        => MoneyHelper::format($item->cashBalance),
            'cashOperations'     => $item->cashOperations,
            'terminalBalance'    => MoneyHelper::format($item->terminalBalance),
            'terminalOperations' => $item->quantityChecks,
            'transactions'       => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
