<?php

namespace App\Http\Resources\Web;

use App\Models\Store\Filial;
use Illuminate\Http\Resources\Json\JsonResource;

class FilialResource extends JsonResource
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
         * @var Filial $item
         */
        return [

            'id'           => $item->id,
            'name'         => $item->name,
            'city'         => $item->city,
            'address'      => $item->address,
            'minOrderCost' => $item->min_order_cost,
        ];
    }
}
