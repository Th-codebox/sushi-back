<?php

namespace App\Http\Controllers\CRM\Store;

use App\Enums\PromoCodeAction;
use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\PromoCode\{UpdatePromoCode, StorePromoCode};
use App\Http\Resources\CRM\PromoCodeResource;
use App\Services\CRM\Store\PromoCodeService;


class PromoCodeController extends BaseCRMController
{
    public function __construct(PromoCodeService $service)
    {
        parent::__construct($service, StorePromoCode::class, UpdatePromoCode::class, PromoCodeResource::class);
    }

    protected function conditions(): array
    {
        $conditions = parent::conditions(); // TODO: Change the autogenerated stub

        if (request()->get('findName')) {
            $conditions['findName'] = request()->get('findName');
        }
        if (request()->get('findCode')) {
            $conditions['findCode'] = request()->get('findCode');
        }
        if (request()->get('type')) {
            $conditions['type'] = request()->get('type');
        }
        $conditions['noFriendGift'] = true;
        $conditions['sort'] = 'sortAsc';
        return $conditions;
    }

}