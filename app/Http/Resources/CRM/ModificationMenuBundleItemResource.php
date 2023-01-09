<?php

namespace App\Http\Resources\CRM;


use App\Models\Store\Modification;
use App\Libraries\Helpers\MoneyHelper;;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class ModificationResource
 * @package App\Http\Resources\CRM
 */
class ModificationMenuBundleItemResource extends JsonResource
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
         * @var Modification $item
         */
        return [
            'id'      => (int)$item->id,
            'name'    => (string)$modification->name,
            'nameOff' => (string)$modification->name_off,
            'nameOn'  => (string)$modification->name_on,
        ];
    }
}
