<?php

namespace App\Http\Resources\Courier;

use App\Models\System\User;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryForm
 * @package App\Http\Resources\CRM\Category
 */
class CourierResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var User $item
         */
        $item = $this->resource;

        $item->scoperFullName();
        date_default_timezone_set('UTC');
        return [
            'id'          => (int)$item->id,
            'name'        => (string)$item->fullName,
            'phone'       => (string)$item->phone,
            'email'       => (string)$item->email,
            'status'      => $item->status,
            'lastVisitAt' => (($item->last_visit_at !== null) ? $item->last_visit_at->unix() : null),
        ];
    }
}
