<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Filial;
use App\Models\Store\FilialSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class FilialSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var FilialSetting $item
         */


        return [
            'id'       => $item->id,
            'filialId' => $item->filial_id,
            'value'    => $item->json ? json_decode($item->value) : $item->value,
            'json'     => $item->json,
        ];
    }
}
