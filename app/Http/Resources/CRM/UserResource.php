<?php

namespace App\Http\Resources\CRM;

use App\Libraries\Image\ImageModify;
use App\Models\System\User;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class UserResource
 * @package App\Http\Resources\CRM
 */
class UserResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;

        /**
         * @var User $item
         */

        $item->scoperFullName();

        return [
            'id'        => $item->id,
            'name'      => $item->fullName,
            'firstName' => $item->first_name,
            'lastName'  => $item->last_name,
            'surname'   => $item->surname,
            'phone'     => $item->phone,
            'image'     => ImageModify::getInstance()->resize($item->image),
            'imagePath' => $item->image,
            'email'     => $item->email,
            'status'    => $item->status,
            'createdAt' => (($item->created_at !== null) ? $item->created_at->format('Y-m-d H:i:s') : null),
            'updatedAt' => (($item->updated_at !== null) ? $item->updated_at->format('Y-m-d H:i:s') : null),
            'role'      => (new RoleResource($this->whenLoaded('role'))),
            'filials'   => FilialResource::collection($this->whenLoaded('filials')),
            'docs'      => UserDocResource::collection($this->whenLoaded('docs')),
        ];
    }
}
