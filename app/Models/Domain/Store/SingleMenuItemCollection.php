<?php


namespace App\Models\Domain\Store;


use App\Enums\ManufacturerType;
use App\Exceptions\Models\ModelNotFoundException;
use Illuminate\Support\Collection;

/*
 * Колекция простых (не составных) блюд + модификатор
 */
class SingleMenuItemCollection extends Collection
{
    public function filterByManufacturerType(ManufacturerType $type)
    {
        return $this->filter(fn(SingleMenuItem $item) => $item->getManufacturerType()->is($type));
    }

    /**
     * @param int $basketItemId
     * @param int $menuItemId
     * @return SingleMenuItem
     * @throw ModelNotFoundException
     */
    public function getItem(int $basketItemId, int $menuItemId): SingleMenuItem
    {
        $item = $this->first(
            fn(SingleMenuItem $item) => $item->basketItemId == $basketItemId && $menuItemId == $item->menuItem->id
        );

        if (empty($item)) throw new ModelNotFoundException("Элемент не найден");

        return $item;
    }
}
