<?php

namespace App\Http\Resources\CRM;

use App\Models\System\WorkSpace;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class WorkSpaceResource
 * @package App\Http\Resources\CRM
 */
class WorkSpaceResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var WorkSpace $item
         */
        $item = $this->resource;

        return [
            'id'             => $item->id,
            'name'           => $item->name,
            'description'    => $item->description,
            'additionalInfo' => $item->additional_info,
            'isReserve'      => $item->is_reserve,
            'phoneAccount'   => $item->phone_account,
            'filial'         => (new FilialResource($this->whenLoaded('filial'))),

        ];
    }
}
