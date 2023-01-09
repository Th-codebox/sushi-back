<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Image\ImageModify;
use App\Models\Store\Collection;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CollectionResource
 * @package App\Http\Resources\CRM
 */
class CollectionResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        /**
         * @var Collection $item
         */
        $item = $this->resource;

        return [
            'id'          => (int)$item->id,
            'name'        => (string)$item->name,
            'subTitle'    => (string)$item->sub_title,
            'slug'        => (string)$item->slug,
            'ico'         => (string)ImageModify::getInstance()->resize($item->ico),
            'types'       => CollectionTypeResource::collection($this->whenLoaded('types')),
            //   'type'        => (string)$item->types,
            'target'      => (string)$item->target,
            'description' => (string)$item->description,
            'status'      => (bool)$item->status,
            'sortOrder'   => (int)$item->sort_order,
            'createdAt'   => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'   => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'menuItems'   => MenuItemResource::collection($this->whenLoaded('menuItems')),
            'category'    => (new CategoryResource($this->whenLoaded('category'))),
        ];
    }
}
