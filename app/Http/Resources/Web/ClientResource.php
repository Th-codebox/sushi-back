<?php

namespace App\Http\Resources\Web;

use App\Models\System\Client;
use App\Services\CRM\Store\ClientPromoCodeService;
use Carbon\Carbon;
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
        /**
         * @TODO countFriendPercent
         */
        return [
            'id'                 => $item->id,
            'name'               => $item->name,
            'phone'              => $item->phone,
            'email'              => $item->email,
            'status'             => $item->status,
            'referralPromoCode'  => $item->referral_promo_code,
            'countFriendPercent' => ClientPromoCodeService::count(['clientId' => $item->id,'promoCodeId' => 2, 'activated' => false]),
            'birthday'           => $item->birthday,
            'birthdayFormatted'           => $item->birthday
                ? (new Carbon($item->birthday))->format('d.m.Y') : '',
            'lastVisitAt'        => (($item->last_visit_at !== null) ? $item->last_visit_at->format('Y-m-d H:i:s') : null),
            'clientAddresses'    => ClientAddressResource::collection($this->whenLoaded('clientAddresses')),
            'orders'             => OrderResource::collection($this->whenLoaded('orders')),
            'clientPromoCodes'   => ClientPromoCodeResource::collection($this->whenLoaded('actualClientPromoCodes')),
        ];
    }
}
