<?php

namespace App\Http\Resources\Web;

use App\Models\Order\BasketItem;
use App\Libraries\Helpers\MoneyHelper;

;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class BasketResource
 * @package App\Http\Resources\Web
 */
class BasketItemResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var BasketItem $item
         */

        $item->selectActiveModification();
        $item->actualTechCard();

        if ($item->technicalCard) {
            $techCard = (new \App\Http\Resources\Web\TechnicalCardResource($item->technicalCard));
        }
        return [

            'id'                     => (int)$item->id,
            'type'                   => (string)$item->type->value,
            'comment'                => (string)$item->comment,
            'free'                   => (boolean)$item->free,
            'price'                  => MoneyHelper::format($item->price),
            'modificationMenuItem'   => (new ModificationMenuItemResource($this->whenLoaded('modificationMenuItem'))),
            'menuItem'               => (new MenuItemResource($this->whenLoaded('menuItem'))),
            'subMenuItem'            => (new MenuItemResource($this->whenLoaded('subMenuItem'))),
            'techCardForConstruct'   => $techCard ?? null,
            'menuItemId'             => $item->menu_item_id ?? null,
            'modificationMenuItemId' => $item->modification_menu_item_id ?? null,
            'subMenuItemId'          => $item->sub_menu_item_id ?? null,
        ];
    }
}
