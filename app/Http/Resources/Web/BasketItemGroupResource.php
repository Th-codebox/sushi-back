<?php

namespace App\Http\Resources\Web;

use App\Libraries\DTO\BasketGroupItem;
use App\Models\Order\BasketItem;
use App\Libraries\Helpers\MoneyHelper;

;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class BasketItemGroupResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var BasketGroupItem $item
         */

        //   print_r($item->price);die;

        return [

            //   'id'                     => (int)$item->id,
            'quantity'               => (int)$item->quantity,
            'weight'                 => (int)$item->weight,
            'type'                   => (string)$item->type,
            'comment'                => (string)$item->comment,
            'free'                   => (boolean)$item->free,
            'price'                  => MoneyHelper::format($item->price),
            'modificationMenuItem'   => (new ModificationMenuItemResource($item->modificationMenuItem)),
            'menuItem'               => (new MenuItemResource($item->menuItem)),
            'subMenuItem'            => (new MenuItemResource($item->subMenuItem)),
            //       'techCardForConstruct'   => $techCard ?? null,
            'menuItemId'             => $item->menuItemId ?? null,
            'modificationMenuItemId' => $item->modificationMenuItemId ?? null,
            'subMenuItemId'          => $item->subMenuItemId ?? null,
            'idsString'              => $item->idsString ?? null,
        ];
    }
}
