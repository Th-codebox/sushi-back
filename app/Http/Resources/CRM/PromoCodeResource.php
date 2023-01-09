<?php

namespace App\Http\Resources\CRM;


use App\Libraries\Helpers\MoneyHelper;
use App\Libraries\Image\ImageModify;
use App\Models\Store\PromoCode;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class PromoCodeResource
 * @package App\Http\Resources\CRM
 */
class PromoCodeResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $item = $this->resource;
        /**
         * @var PromoCode $item
         */
        return [
            'id'                       => $item->id,
            'name'                     => $item->name,
            'description'              => $item->description,
            'code'                     => $item->code,
            'salePercent'              => $item->sale_percent,
            'saleSubtraction'          => MoneyHelper::format($item->sale_subtraction),
            'saleMenuItem'             => (new MenuItemResource($this->whenLoaded('saleMenuItem'))),
            'saleModificationMenuItem' => (new ModificationMenuItemResource($this->whenLoaded('saleModificationMenuItem'))),
            'timeOn'                  => $item->time_on ? $item->time_on->unix() : null,
            'timeEnd'                 => $item->time_end ? $item->time_end->unix() : null,
            'timeAvailableFrom'        => $item->time_available_from ? $item->time_available_from->format('H:i') : null,
            'timeAvailableTo'          => $item->time_available_to ? $item->time_available_to->format('H:i') : null,
            'type'                     => (string)$item->type,
            'action'                   => (string)$item->action,
            'onlyFirstOrder'           => (bool)$item->only_first_order,
            'status'                   => (bool)$item->status,
            'sortOrder'               => $item->sort_order,
            'repeat'                   => $item->repeat,
            'daysForActive'            => $item->days_for_active,
        ];
    }
}
