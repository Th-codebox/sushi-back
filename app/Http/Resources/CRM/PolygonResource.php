<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Filial;
use App\Models\System\Polygon;
use App\Libraries\Helpers\MoneyHelper;

;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PolygonResource extends JsonResource
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
         * @var Polygon $item
         */
        return [

            'id'            => $item->id,
            'name'          => $item->name,
            'color'         => $item->color,
            'type'          => (string)$item->type,
            'price'         => MoneyHelper::format($item->price),
            'freeFromPrice' => MoneyHelper::format($item->free_from_price),
            'time'          => Carbon::createFromFormat('H:i:s', $item->time)->secondsSinceMidnight(),
            'area'          => $item->area,
            'sortOrder'     => $item->sort_order,
            'status'        => $item->status,
            'createdAt'     => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'     => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),

        ];
    }
}
