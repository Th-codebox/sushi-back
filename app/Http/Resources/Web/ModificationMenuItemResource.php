<?php

namespace App\Http\Resources\Web;

use App\Models\Store\Modification;
use App\Models\Store\ModificationMenuItem;
use App\Libraries\Helpers\MoneyHelper;;
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
            'id'                           => (int)$item->id,
            'modification_id'              => (int)$modification->id,
            'actualPrice'                  => MoneyHelper::format($item->actualPrice),
            'actualCharacteristicTechCard' => (new TechnicalCardResource($item->actualTechnicalCard))->additional($this->additional),
            'name'                         => (string)$modification->name,
            'action'                       => (string)$modification->action,
            'type'                         => (string)$modification->type,
            'nameOff'                      => (string)$modification->name_off,
            'nameOn'                       => (string)$modification->name_on,
            'active'                       => (bool)$item->activeModification,
        ];
    }
}
