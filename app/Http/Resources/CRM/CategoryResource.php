<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Image\ImageModify;
use App\Models\Store\Category;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryResource
 * @package App\Http\Resources\CRM
 */
class CategoryResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var Category $item
         */
        return [
            'id'              => (int)$item->id,
            'name'            => (string)$item->name,
            'slug'            => (string)$item->slug,
            'description'     => (string)$item->description,
            'h1'              => (string)$item->h1,
            'metaTitle'       => (string)$item->meta_title,
            'metaDescription' => (string)$item->meta_description,
            'status'          => (bool)$item->status,
            'sortOrder'       => (int)$item->sort_order,
            'ico'             => ImageModify::getInstance()->resize($item->ico),
            'icoPath' => $item->ico,
            'updatedAt'       => $item->updated_at->toDateTimeString(),
            'createdAt'       => $item->created_at->toDateTimeString(),
            'menuItems'       => MenuItemResource::collection($this->whenLoaded('menuItems')),
            'collections'     => CollectionResource::collection($this->whenLoaded('collections')),
        ];
    }
}
