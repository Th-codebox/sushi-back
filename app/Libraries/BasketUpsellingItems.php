<?php


namespace App\Libraries;

use App\Enums\DishType;
use App\Models\Order\Basket;
use App\Models\Order\BasketItem;
use App\Models\Store\MenuItem;
use App\Services\CRM\Store\MenuItemService;
use Illuminate\Support\Collection;

class BasketUpsellingItems
{
    private Basket $basket;
    public Collection $upSellingItems;

    /**
     * BasketUpsellingItems constructor.
     * @param Basket $basket
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
        $this->upSellingItems = new Collection();

        $this->generateList();
        $this->checkOnExistInBasket();
    }

    /**
     * @return Collection
     */
    public function getUpSellingItems(): Collection
    {
        return $this->upSellingItems;
    }

    /**
     * @return array
     */
    private function rule(): array
    {
        return [];
    }


    /**s
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    private function generateList(): void
    {
        $drinks = MenuItemService::findList(['dishType' => DishType::Drink]);

        foreach ($drinks as $key => $drink) {
            /**
             * @var MenuItemService $drink
             */
            $this->upSellingItems->push($drink->getRepository()->getModel());

            if ($key === 2) {
                break;
            }
        }
        try {
            $this->upSellingItems->push(MenuItemService::findOne(['name' => 'Банана-мама'])->getRepository()->getModel());
        } catch (\Throwable $e) {
        }
        try {
            $this->upSellingItems->push(MenuItemService::findOne(['name' => 'Порционный соус спайси'])->getRepository()->getModel());
        } catch (\Throwable $e) {
        }

        try {
            $this->upSellingItems->push(MenuItemService::findOne(['name' => 'Порционный унаги-соус'])->getRepository()->getModel());
        } catch (\Throwable $e) {
        }

        try {
            $this->upSellingItems->push(MenuItemService::findOne(['!name' => ['Порционный унаги-соус', 'Порционный соус спайси']])->getRepository()->getModel());
        } catch (\Throwable $e) {

        }
    }

    private function checkOnExistInBasket()
    {

        $this->upSellingItems = $this->upSellingItems->map(function (MenuItem $item) {

            $this->basket->items->map(function (BasketItem $basketItem) use (&$item) {
                if($basketItem->menu_item_id === $item->id) {
                    $item->existInBasket = true;
                }
            });

            return $item;
        });
    }

}
