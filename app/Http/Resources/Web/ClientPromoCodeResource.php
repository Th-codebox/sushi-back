<?php

namespace App\Http\Resources\Web;


use App\Models\Store\ClientPromoCode;
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
            'id'          => (int)$item->id,
            'promoCode'   => (new PromoCodeResource($this->whenLoaded('promoCode'))),
            'activated'   => (bool)$item->activated,
            'deadLine'    => $item->dead_line ? $item->dead_line->toDateString() : null,
            'dateBegin'   => $item->date_begin ? $item->date_begin->toDateString() : null,
            'activatedAt' => $item->activated_at,
            'createAt'    => $item->created_at,
            'quantity'    => (int)$item->quantity,
        ];
    }
}
