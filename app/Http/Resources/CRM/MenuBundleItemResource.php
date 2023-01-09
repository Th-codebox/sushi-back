<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\TechnicalCard;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class TechnicalCardResource
 * @package App\Http\Resources\CRM
 */
class MenuBundleItemResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var TechnicalCard $item
         */


        return [
            'id'                   => (int)$item->id,
            'menuItem'             => (new MenuItemBundleResource($this->whenLoaded('menuItem'))),
            'modificationMenuItem' => (new ModificationMenuBundleItemResource($this->whenLoaded('modificationMenuItem'))),
        ];
    }
}
