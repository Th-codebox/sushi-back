<?php

namespace App\Http\Resources\Web;


use App\Libraries\Image\ImageModify;
use App\Models\Store\News;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class NewsResource
 * @package App\Http\Resources\CRM
 */
class NewsResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var News $item
         */
        return [
            'id'                         => (int)$item->id,
            'name'                       => (string)$item->name,
            'subName'                    => (string)$item->sub_name,
            'slug'                       => (string)$item->slug,
            'description'                => (string)$item->description,
            'descriptionBeforePromoCode' => (string)$item->description_before_promo_code,
            'descriptionAfterPromoCode'  => (string)$item->description_after_promo_code,
            'h1'                         => (string)$item->h1,
            'metaTitle'                  => (string)$item->meta_title,
            'metaDescription'            => (string)$item->meta_description,
            'status'                     => (bool)$item->status,
            'sortOrder'                  => (int)$item->sort_order,
            'image'                      => ImageModify::getInstance()->resize($item->image),
            'date'                       => $item->date->format('d-m-y'),
            'promoCode'                  => (new PromoCodeResource($this->whenLoaded('promoCode'))),
        ];
    }
}
