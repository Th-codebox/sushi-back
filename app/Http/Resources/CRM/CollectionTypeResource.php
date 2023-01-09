<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Image\ImageModify;
use App\Models\Store\CollectionType;
use App\Models\Store\MenuItem;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CollectionTypeResource
 * @package App\Http\Resources\CRM
 */
class CollectionTypeResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        /**
         * @var CollectionType $item
         */
        $item = $this->resource;

        return $item->value->value;
    }
}
