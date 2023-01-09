<?php

namespace App\Http\Resources\CRM;


use App\Http\Resources\Web\TechnicalCardResource;
use App\Models\Store\Modification;
use App\Libraries\Helpers\MoneyHelper;

;

use App\Models\Store\ModificationMenuItem;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class ModificationResource
 * @package App\Http\Resources\CRM
 */
class ModificationMenuItemResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;

        /**
         * @var Modification $modification
         */
        $modification = (new ModificationResource($this->whenLoaded('modification')));

        /**
         * @var ModificationMenuItem $item
         */
        return [
            'id' => (int)$item->id,

            'actualPrice'                  => MoneyHelper::format($item->actualPrice),
            'actualCharacteristicTechCard' => (new TechnicalCardResource($item->actualTechnicalCard))->additional($this->additional),
            'priceRate'                    => (float)$item->price_rate,
            'priceAdd'                     => MoneyHelper::format($item->price_add),
            'status'                       => (bool)$item->status,
            'sortOrder'                    => (int)$item->sort_order,
            'updatedAt'                    => $item->updated_at->toDateTimeString(),
            'createdAt'                    => $item->created_at->toDateTimeString(),
            /*    'menuItem'      => (new MenuItemResource($this->whenLoaded('menuItem'))),*/
            'modification'                 => (new ModificationResource($this->whenLoaded('modification'))),
        ];
    }
}
