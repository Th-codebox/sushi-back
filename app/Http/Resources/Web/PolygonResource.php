<?php

namespace App\Http\Resources\Web;

use App\Models\System\Polygon;
use App\Libraries\Helpers\MoneyHelper;


use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'     => $item->id,
            'name'   => $item->name,
            'area'   => $item->getArrayCoords(),
            'type'   => (string)$item->type,
            'color'  => $item->color,
            'price'  => MoneyHelper::format($item->price),
            'filial' => (new FilialResource($this->whenLoaded('filial'))),
        ];
    }
}
