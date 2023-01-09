<?php

namespace App\Http\Resources\Web;

use App\Models\Store\TechnicalCard;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Libraries\Image\ImageModify;


/**
 * Class TechnicalCardItem
 * @package App\Http\Resources\Web\TechnicalCard
 */
class TechnicalCardResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;

        /**
         * @var TechnicalCard $item
         */


        return [
            //'id'            => (int)$item->id,
            // 'name'          => (string)$item->name,
            'weight'        => (int)$item->weight,
            'proteins'      => (int)$item->proteins,
            'fats'          => (int)$item->fats,
            'carbohydrates' => (int)$item->carbohydrates,
            'calories'      => (int)$item->calories,
        ];
    }
}
