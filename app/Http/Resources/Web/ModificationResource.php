<?php

namespace App\Http\Resources\Web;

use App\Models\Store\Modification;
use App\Models\Store\ModificationMenuItem;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class ModificationResource
 * @package App\Http\Resources\CRM
 */
class ModificationResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;

        /**
         * @var Modification $item
         */
        return [
            'id'        => (int)$item->id,
            'name'      => (string)$item->name,
            'description'      => (string)$item->description,
            'action'    => (string)$item->action,
            'type'      => (string)$item->type,
            'priceRate' => (float)$item->price_rate,
            'priceAdd'  => (float)$item->price_add,
            'nameOff'   => (string)$item->name_off,
            'nameOn'    => (string)$item->name_on,
     //       'technicalCard'    => (new TechnicalCardResource($this->whenLoaded('technicalCard'))),
        ];
    }
}
