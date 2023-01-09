<?php


namespace App\Models\Domain\Store;


use App\Enums\ManufacturerType;
use App\Libraries\Image\ImageModify;
use App\Models\Store\MenuItem;
use App\Models\Store\ModificationMenuItem;
use Illuminate\Contracts\Support\Arrayable;

class SingleMenuItem implements Arrayable
{
    public int $orderId;
    public int $basketItemId;
    public MenuItem $menuItem;
    public ?ModificationMenuItem $modification;

    /**
     * SingleMenuItem constructor.
     * @param int $orderId
     * @param int $basketItemId
     * @param MenuItem $menuItem
     * @param ModificationMenuItem|null $modification
     */
    public function __construct(
        int $basketItemId,
        MenuItem $menuItem,
        ?ModificationMenuItem $modification)
    {
        $this->basketItemId = $basketItemId;
        $this->menuItem = $menuItem;
        $this->modification = $modification;
    }


    public function getManufacturerType(): ManufacturerType
    {
        if ($this->menuItem->technicalCard) {
            return $this->menuItem->technicalCard->manufacturer_type;
        }

        throw new \InvalidArgumentException("У блюда {$this->menuItem->name} не указано место приготовления (цех) или не задана техническая карта");
    }

    public function getModificationName(): string
    {
        return $this->modification->modification->name ?? '';
    }

    public function getImgAbsolutePath()
    {
        if ($this->menuItem->image) {
            return ImageModify::getInstance()->resize($this->menuItem->image);
        }
        return '';
    }

    public function toArray()
    {
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value instanceof Arrayable ? $value->toArray() : $value;
        }
        return $data;
    }

}
