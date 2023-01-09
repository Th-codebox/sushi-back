<?php

namespace App\Http\Resources\Web;

use App\Models\System\Client;
use App\Models\System\ClientAddress;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class CategoryForm
 * @package App\Http\Resources\CRM\Category
 */
class ClientAddressResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        /**
         * @var ClientAddress $item
         */
        $item = $this->resource;

        return [
            'id'              => (int)$item->id,
            'name'            => (string)$item->name,
            'apartmentNumber' => (string)$item->apartment_number,
            'icoName'         => (string)$item->ico_name,
            'city'            => (string)$item->city,
            'street'          => (string)$item->street,
            'house'           => (string)$item->house,
            'entry'           => (string)$item->entry,
            'latGeo'          => (string)$item->lat_geo,
            'letGeo'          => (string)$item->let_geo,
            'floor'           => (string)$item->floor,
        ];
    }
}
