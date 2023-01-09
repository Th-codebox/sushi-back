<?php


namespace App\Libraries\DTO;




use App\Models\Order\BasketItem;
use App\Models\Store\MenuItem;
use App\Models\Store\ModificationMenuItem;

/**
 * Class BasketGroupItems
 * @package App\Libraries\DTO
 * @property int $quantity
 * @property int $basketId
 * @property int $modificationMenuItemId
 * @property int $menuItemId
 * @property int $subMenuItemId
 * @property string $type
 * @property int $price
 * @property boolean $free
 * @property string $comment
 * @property string $name
 * @property string $fullName
 * @property string $menuItemName
 * @property string $modificationMenuItemName
 * @property string $modificationMenuItemNameOn
 * @property string $modificationMenuItemNameOff
 * @property string $idsString
 * @property MenuItem $menuItem
 * @property ?MenuItem $subMenuItem
 * @property ?ModificationMenuItem $modificationMenuItem
 */
class BasketGroupItem
{

    public int $quantity;
    public int $basketId;
    public ?int $modificationMenuItemId;
    public ?int $menuItemId;
    public ?int $subMenuItemId;
    public string $type;
    public ?int $price;
    public ?int $weight;
    public ?string $menuItemName;
    public ?string $modificationMenuItemName;
    public ?string $modificationMenuItemNameOn;
    public ?string $modificationMenuItemNameOff;
    public ?string $comment;
    public ?string $fullName;
    public ?string $name;
    public ?string $idsString;
    public ?bool $free;
    public MenuItem $menuItem;
    public ?MenuItem $subMenuItem;
    public ?ModificationMenuItem $modificationMenuItem;

    public function __construct(BasketItem $item)
    {

        $this->menuItemName = $item->menuItem->name;
        $this->modificationMenuItemName =  $item->modificationMenuItem ? $item->modificationMenuItem->modification->name : null;
        $this->modificationMenuItemNameOn =  $item->modificationMenuItem ? $item->modificationMenuItem->modification->name_on : null;
        $this->modificationMenuItemNameOff =  $item->modificationMenuItem ? $item->modificationMenuItem->modification->name_off : null;
        $this->name = $item->fullName;
        $this->quantity = $item->quantity;
        $this->weight = $item->weight;
        $this->basketId = $item->basket_id;
        $this->modificationMenuItemId = $item->modification_menu_item_id;
        $this->menuItemId = $item->menu_item_id;
        $this->subMenuItemId = $item->sub_menu_item_id;
        $this->type = $item->type;
        $this->price = $item->price;
        $this->comment = $item->comment;
        $this->free = $item->free;
        $this->menuItem = $item->menuItem;
        $this->subMenuItem = $item->subMenuItem;
        $this->modificationMenuItem = $item->modificationMenuItem;
        $this->idsString = $item->idsString;
    }
}


