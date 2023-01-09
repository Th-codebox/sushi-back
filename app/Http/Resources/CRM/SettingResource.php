<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
         * @var Setting $item
         */

        return [

            'settingId'   => $item->id,
            'name'        => $item->name,
            'key'         => $item->key,
            'group'       => $item->group,
            'value'       => $item->json ? json_decode($item->value) : $item->value,
            'json'        => $item->json,
            'filialSetting' => FilialSettingResource::collection($this->whenLoaded('valueFilials')),
        ];
    }
}
