<?php

namespace App\Http\Resources\CRM;


use App\Models\System\Permission;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class PermissionResource
 * @package App\Http\Resources\CRM
 */
class PermissionResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;



        /**
         * @var Permission $item
         */
        return [
            'id'         => $item->id,
            'name'       => $item->name,
            'namespace'  => $item->namespace,
            'controller' => $item->controller,
            'method'     => $item->method,
            'group'      => $item->group,
            'sort_order' => $item->sort_order,
            'updatedAt'  =>  $item->updated_at->toDateTimeString(),
            'type' => $this->whenPivotLoaded('role_permission', function () {
                return  $this->pivot->type;
            })

        ];
    }
}
