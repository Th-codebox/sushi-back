<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Image\ImageModify;
use App\Models\Store\Promotion;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class PromotionResource
 * @package App\Http\Resources\CRM
 */
class PromotionResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var Promotion $item
         */
        return [
            'id'              => (int)$item->id,
            'name'            => (string)$item->name,
            'subName'         => (string)$item->sub_name,
            'slug'            => (string)$item->slug,
            'description'     => (string)$item->description,
            'textOne'           => (string)$item->text_one,
            'textTwo'           => (string)$item->text_two,
            'imgBgrColor'     => (string)$item->img_bgr_color,
            'h1'              => (string)$item->h1,
            'metaTitle'       => (string)$item->meta_title,
            'metaDescription' => (string)$item->meta_description,
            'status'          => (bool)$item->status,
            'sortOrder'       => (int)$item->sort_order,
            'image'           => ImageModify::getInstance()->resize($item->image),
            'imagePath'       => $item->image,
            'mobileImage'     => ImageModify::getInstance()->resize($item->mobile_image),
            'mobileImagePath' => $item->mobile_image,
            'color'           => $item->color,
            'dateBegin'       => $item->date_begin ? $item->date_begin->toDateString() : null,
            'dateEnd'         => $item->date_end ? $item->date_end->toDateString() : null,
            'updatedAt'       => $item->updated_at->toDateTimeString(),
            'createdAt'       => $item->created_at->toDateTimeString(),
            'promoCodeId'     => $item->promo_code_id,
            'promoCode'       => (new PromoCodeResource($this->whenLoaded('promoCode'))),
        ];
    }
}
