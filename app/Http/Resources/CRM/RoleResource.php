<?php

namespace App\Http\Resources\CRM;

use App\Models\System\CustomUserPermission;
use App\Models\System\Role;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CustomPermissionResource
 * @package App\Http\Resources\CRM
 */
class RoleResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var Role $item
         */

        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'sortOrder' => $item->sort_order,
            'status'    => $item->status,
            'createdAt'   => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt'   => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
