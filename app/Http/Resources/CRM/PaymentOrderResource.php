<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Helpers\MoneyHelper;
use App\Models\System\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentOrderResource extends JsonResource
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
         * @var Payment $item
         */

        return [
            'paymentLink' => $item->payment_link,
            'total'       => MoneyHelper::format($item->total),
            'status'      => $item->payment_status,
        ];
    }
}
