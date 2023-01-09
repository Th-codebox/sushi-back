<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Image\ImageModify;
use App\Models\Store\Slider;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class SliderResource
 * @package App\Http\Resources\CRM
 */
class SliderResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var Slider $item
         */
        return [
            'id'           => (int)$item->id,
            'name'         => (string)$item->name,
            'color'         => (string)$item->color,
            'link'         => (string)$item->link,
            'target'         => (string)$item->target,
            'desktopImage' => ImageModify::getInstance()->resize($item->desktop_image),
            'pathDesktopImage' => $item->desktop_image,
            'mobileImage'  => ImageModify::getInstance()->resize($item->mobile_image),
            'pathMobileImage'  => $item->mobile_image,
            'status'       => (bool)$item->status,
            'sortOrder'    => (int)$item->sort_order,
            'updatedAt'    => $item->updated_at->toDateTimeString(),
            'createdAt'    => $item->created_at->toDateTimeString(),
        ];
    }
}
