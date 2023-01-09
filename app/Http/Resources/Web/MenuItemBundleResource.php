<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\CRM\ModificationMenuBundleItemResource;
use App\Libraries\Image\ImageModify;
use App\Models\Store\MenuItem;
use App\Libraries\Helpers\MoneyHelper;;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class MenuItemResource
 * @package App\Http\Resources\CRM
 */
class MenuItemBundleResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var MenuItem $item
         */

        return [
            'id'                   => (int)$item->id,
            'menuItem'             => (new \App\Http\Resources\CRM\MenuItemBundleResource($this->whenLoaded('menuItem'))),
            'modificationMenuItem' => (new ModificationMenuBundleItemResource($this->whenLoaded('modificationMenuItem'))),
        ];
    }
}
