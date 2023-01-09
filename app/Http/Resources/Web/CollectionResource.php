<?php

namespace App\Http\Resources\Web;


use App\Libraries\Image\ImageModify;
use App\Models\Store\Collection;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CollectionItem
 * @package App\Http\Resources\CRM\Collection
 */
class CollectionResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;

        /**
         * @var Collection $item
         */

        return [
            'id'          => (int)$item->id,
            'name'        => (string)$item->name,
            'subTitle'    => (string)$item->sub_title,
            'image'       => ImageModify::getInstance()->resize($item->image),
            'slug'        => (string)$item->slug,
            'ico'         => (string)$item->ico,
            'type'        => (string)$item->type,
            'target'      => (string)$item->target,
            'description' => (string)$item->description,
            'menuItems'   => MenuItemResource::collection($this->whenLoaded('menuItems')),
            'category'    => (new CategoryResource($this->whenLoaded('category'))),
        ];
    }
}
