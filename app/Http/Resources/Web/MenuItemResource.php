<?php

namespace App\Http\Resources\Web;

use App\Http\Resources\CRM\MenuBundleItemResource;
use App\Http\Resources\CRM\SouseResource;
use App\Libraries\Helpers\MoneyHelper;
use App\Libraries\Image\ImageModify;
use App\Models\Store\MenuItem;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class MenuItemItem
 * @package App\Http\Resources\Web\MenuItem
 */
class MenuItemResource extends JsonResource
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

        $item->calcModifiedPrice();
        $item->calcModifiedTechCard(true);


        return [
            'id'               => (int)$item->id,
            'name'             => (string)$item->name,
            'slug'             => (string)$item->slug,
            'description'      => (string)$item->description,
            'h1'               => (string)$item->h1,
            'metaTitle'        => (string)$item->meta_title,
            'metaDescription'  => (string)$item->meta_description,
            'price'            => MoneyHelper::format($item->price),
            'type'             => (string)$item->type,
            'oldPrice'         => MoneyHelper::format($item->old_price),
            'bonusAdd'         => (int)$item->bonus_add,
            'stickerType'      => (string)$item->sticker_type,
            'stickerMarketing' => (string)$item->sticker_marketing,
            'hasStop'          => (bool)$item->has_stop,
            'needPersonCount'  => (bool)$item->need_person_count,
            'image'            => ImageModify::getInstance()->resize($item->image),
            'imagePath'        => $item->image,
            'composition'      => (string)$item->composition,
            'hide'             => (boolean)$item->hide,
            'existInBasket'    => (boolean)$item->existInBasket,
            'souse'            => (new SouseResource($this->whenLoaded('souse'))),
            'modifications'    => ModificationMenuItemResource::collection($this->whenLoaded('modifications')),
            'collections'      => CollectionResource::collection($this->whenLoaded('collections')),
            'categories'       => CategoryResource::collection($this->whenLoaded('categories')),
            'technicalCard'    => $item->actualTechnicalCard ? (new TechnicalCardResource($item->actualTechnicalCard)) : (new TechnicalCardResource($this->whenLoaded('technicalCard'))),
            'bundleItems'      => MenuItemBundleResource::collection($this->whenLoaded('bundleItems')),
        ];
    }
}
