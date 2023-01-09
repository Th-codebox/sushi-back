<?php

namespace App\Http\Resources\Courier;

use App\Libraries\Image\ImageModify;
use App\Models\System\User;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryForm
 * @package App\Http\Resources\CRM\Category
 */
class CourierProfileResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function toArray($request)
    {
        /**
         * @var User $item
         */
        $item = $this->resource;

        $item->scoperFullName();
        $item->information();
        $item->searchActualFilial();

        return [
            'id'              => (int)$item->id,
            'name'            => (string)$item->fullName,
            'phone'           => (int)$item->phone,
            'email'           => (string)$item->email,
            'photo'           => ImageModify::getInstance()->resize($item->image),
            'todayDeliveries' => (int)$item->todayDeliveries,
            'todayLateness'   => (int)$item->todayLateness,
            'allDeliveries'   => (int)$item->allDeliveries,
            'allLateness'     => (int)$item->allLateness,
            'allDays'         => (int)$item->allDays,
            'workedShifts'    => (int)$item->workedShifts,
            'filialAddress'   => $item->actualFilial->address ?? "",
            'letGeo'          => $item->actualFilial->let ?? "",
            'latGeo'          => $item->actualFilial->lat ?? "",
            'adminPhone'      => '+' . $item->actualFilial->work_phone ?? "",
        ];
    }
}
