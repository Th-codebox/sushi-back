<?php

namespace App\Http\Resources\CRM\Order;

use App\Models\Domain\Store\SingleMenuItem;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleMenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var SingleMenuItem $item */
        $item = $this->resource;
        return [
            'basketItemId' => $item->basketItemId,
            'menuItemId' => $item->menuItem->id,
            'name' => $item->menuItem->name,
            'image' => $item->getImgAbsolutePath(),
            'hasModification' => !!$item->getModificationName(),
            'modificationName' => $item->getModificationName(),
        ];
    }
}
