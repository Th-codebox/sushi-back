<?php

namespace App\Http\Resources\CRM;

use App\Libraries\Image\ImageModify;
use App\Models\Store\MenuItem;
use App\Libraries\Helpers\MoneyHelper;

;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class MenuItemResource
 * @package App\Http\Resources\CRM
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
            'oldPrice'         => MoneyHelper::format($item->old_price),
            'bonusAdd'         => (int)$item->bonus_add,
            'type'             => (string)$item->type,
            'stickerType'      => (string)$item->sticker_type,
            'stickerMarketing' => (string)$item->sticker_marketing,
            'image'            => ImageModify::getInstance()->resize($item->image),
            'imagePath'        => $item->image,
            'composition'      => (string)$item->composition,
            'hide'             => (bool)$item->hide,
            'status'           => (bool)$item->status,
            'hasStop'          => (bool)$item->has_stop,
            'needPersonCount'  => (bool)$item->need_person_count,
            'sortOrder'        => (int)$item->sort_order,
            'existInBasket'    => (boolean)$item->existInBasket,
            'createdAt'        => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'        => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'collections'      => CollectionResource::collection($this->whenLoaded('collections')),
            'categories'       => CategoryResource::collection($this->whenLoaded('categories')),
            'modifications'    => ModificationMenuItemResource::collection($this->whenLoaded('modifications')),
            'technicalCard'    => (new TechnicalCardResource($this->whenLoaded('technicalCard'))),
            'technicalCardId' => $item->technical_card_id,
            'souse'            => (new SouseResource($this->whenLoaded('souse'))),
            'bundleItems'      => MenuBundleItemResource::collection($this->whenLoaded('bundleItems')),
        ];
    }
}
