<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Modification;
use App\Libraries\Helpers\MoneyHelper;;
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
            'id'            => (int)$item->id,
            'name'          => (string)$item->name,
            'description'   => (string)$item->description,
            'action'        => (string)$item->action,
            'type'          => (string)$item->type,
            'priceRate'     => (float)$item->price_rate,
            'priceAdd'      => MoneyHelper::format($item->price_add),
            'nameOff'       => (string)$item->name_off,
            'nameOn'        => (string)$item->name_on,
            'status'        => (bool)$item->status,
            'sortOrder'     => (int)$item->sort_order,
            'updatedAt'     => $item->updated_at->toDateTimeString(),
            'createdAt'     => $item->created_at->toDateTimeString(),
            'technicalCard' => (new TechnicalCardResource($this->whenLoaded('technicalCard'))),
        ];
    }
}
