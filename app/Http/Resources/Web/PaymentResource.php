<?php

namespace App\Http\Resources\Web;

use App\Enums\OrderStatus;
use App\Models\Order\Basket;

use App\Models\System\Payment;
use App\Services\CRM\Order\OrderService;
use App\Libraries\Helpers\MoneyHelper;


use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class PaymentResource extends JsonResource
{

    /**
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
