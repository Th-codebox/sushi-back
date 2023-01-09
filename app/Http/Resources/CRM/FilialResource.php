<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Filial;
use App\Repositories\Store\SettingRepository;
use App\Repositories\Store\FilialRepository;
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

        $settings = (new FilialRepository($item))->getSettings();


        return [

            'id'           => $item->id,
            'name'         => $item->name,
            'city'         => $item->city,
            'address'      => $item->address,
            'minOrderCost' => $item->min_order_cost,
            'sortOrder'    => $item->sort_order,
            'status'       => $item->status,
            'createdAt'    => $item->created_at,
            'updatedAt'    => $item->updated_at,
            'settings'     => SettingResource::collection($settings),
        ];
    }
}
