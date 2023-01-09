<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Filial;
use App\Models\System\Polygon;
use App\Models\System\Utm;
use Illuminate\Http\Resources\Json\JsonResource;

class UtmResource extends JsonResource
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
         * @var Utm $item
         */
        return [
            'id'           => $item->id,
            'entity_name'  => $item->entity_name,
            'entity_id'    => $item->entity_id,
            'utm_medium'   => $item->utm_medium,
            'utm_campaign' => $item->utm_campaign,
            'utm_content'  => $item->utm_content,
            'utm_term'     => $item->utm_term,
        ];
    }
}
