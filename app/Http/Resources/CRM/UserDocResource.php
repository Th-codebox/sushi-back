<?php

namespace App\Http\Resources\CRM;

use App\Models\Store\Filial;
use App\Models\System\UserDoc;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDocResource extends JsonResource
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
         * @var UserDoc $item
         */
        return [

            'id'          => $item->id,
            'name'        => $item->name,
            'path'        => $item->path,
            'description' => $item->description,
            'group'       => $item->group,
            'sortOrder'   => $item->sort_order,
            'status'      => $item->status,
            'createdAt'   => $item->created_at,
            'updatedAt'   => $item->updated_at,
        ];
    }
}
