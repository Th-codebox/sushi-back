<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\TechnicalCard;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class TechnicalCardResource
 * @package App\Http\Resources\CRM
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
            'id'                 => (int)$item->id,
            'name'               => (string)$item->name,
            'weight'             => (int)$item->weight,
            'proteins'           => (int)$item->proteins,
            'fats'               => (int)$item->fats,
            'carbohydrates'      => (int)$item->carbohydrates,
            'calories'           => (int)$item->calories,
            'composition'        => (string)$item->composition,
            'compositionForCook' => (string)$item->composition_for_cook,
            'chefComment'        => (string)$item->chef_comment,
            'cookingType'        => (string)$item->cooking_type,
            'dishType'           => (string)$item->dish_type,
            'manufacturerType'   => (string)$item->manufacturer_type,
            'termTime'           => (int)$item->term_time,
            'timeToCool'         => (int)$item->time_to_cool,
            'cookingTime'        => (int)$item->cooking_time,
            'hasTerm'            => (bool)$item->has_term,
            'status'             => (bool)$item->status,
            'sortOrder'          => (int)$item->sort_order,
            'createdAt'          => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'          => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'modelItems'         => MenuItemResource::collection($this->whenLoaded('modelItems')),
        ];
    }
}
