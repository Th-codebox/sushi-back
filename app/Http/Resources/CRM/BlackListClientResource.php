<?php

namespace App\Http\Resources\CRM;

use App\Models\System\BlackListClient;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryForm
 * @package App\Http\Resources\CRM\Category
 */
class BlackListClientResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        /**
         * @var BlackListClient $item
         */
        $item = $this->resource;

        return [
            'id'          => (int)$item->id,
            'name'        => (string)$item->reason,
            'endBlocking' => $item->end_blocking ?: $item->end_blocking->toDateTimeString(),
            'updatedAt'   => $item->updated_at->toDateTimeString(),
            'createdAt'   => $item->created_at->toDateTimeString(),
        ];
    }
}
