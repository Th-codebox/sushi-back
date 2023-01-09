<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Helpers\MoneyHelper;
use App\Libraries\Image\ImageModify;
use App\Models\Store\ClientPromoCode;
use App\Models\Store\PromoCode;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class PromoCodeResource
 * @package App\Http\Resources\CRM
 */
class ClientPromoCodeResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var ClientPromoCode $item
         */
        return [
            'id'             => (int)$item->id,
            'client'         => (new ClientResource($this->whenLoaded('client'))),
            'referralClient' => (new ClientResource($this->whenLoaded('client'))),
            'promoCode'      => (new PromoCodeResource($this->whenLoaded('promoCode'))),
            'order'          => (new OrderResource($this->whenLoaded('order'))),
            'activated'      => (bool)$item->activated,
            'deadLine'       => $item->dead_line,
            'activatedAt'    => $item->activated_at,
            'createAt'       => $item->created_at,
        ];
    }
}
