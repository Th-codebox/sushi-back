<?php

namespace App\Http\Resources\Web;


use App\Models\Store\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Libraries\Image\ImageModify;


/**
 * Class CategoryItem
 * @package App\Http\Resources\Web\Category
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
            'ico'             => ImageModify::getInstance()->resize($item->ico),
            'menuItems'       => MenuItemResource::collection($this->whenLoaded('menuItems')),
            'collections'     => CollectionResource::collection($this->whenLoaded('collections')),
        ];
    }
}
