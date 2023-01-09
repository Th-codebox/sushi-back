<?php

namespace App\Http\Resources\CRM;

use App\Models\System\Client;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryForm
 * @package App\Http\Resources\CRM\Category
 */
class ClientResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        /**
         * @var Client $item
         */
        $item = $this->resource;

        $item->groupClientPromoCodes();

        return [
            'id'                => $item->id,
            'name'              => $item->name,
            'phone'             => $item->phone,
            'email'             => $item->email,
            'status'            => $item->status,
            'confirmPhone'      => $item->confirm_phone,
            'referralPromoCode' => $item->referral_promo_code,
            'birthday'          => $item->birthday,
            'lastVisitAt'       => (($item->last_visit_at !== null) ? $item->last_visit_at->format('Y-m-d H:i:s') : null),
            'clientAddresses'   => ClientAddressResource::collection($this->whenLoaded('clientAddresses')),
            'hasActiveBlock'    => (new BlackListClientResource($this->whenLoaded('hasActiveBlock'))),
            'updatedAt'         => $item->updated_at->toDateTimeString(),
            'createdAt'         => $item->created_at->toDateTimeString(),
        ];
    }
}
