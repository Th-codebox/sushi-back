<?php

namespace App\Http\Resources\CRM;

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
            'id'               => (int)$item->id,
            'name'             => (string)$item->name,
            'description'             => (string)$item->description,
            'stickerType'      => (string)$item->sticker_type,
            'stickerMarketing' => (string)$item->sticker_marketing,
            'image'            => ImageModify::getInstance()->resize($item->image),
            'imagePath' => $item->image,
            'createdAt'        => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'        => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
        ];
    }
}
