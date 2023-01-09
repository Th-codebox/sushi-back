<?php


namespace App\Repositories\Order;


use App\Enums\BasketItemType;
use App\Models\Order\BasketItem;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BasketItemRepository
 * @package App\Repositories\Order
 * @method BasketItem getModel()
 */
class BasketItemRepository extends BaseRepository
{

    protected array $relations = ['menuItem', 'modificationMenuItem','basket'];

    /**
     * BasketItemRepository constructor.
     * @param BasketItem $model
     */
    public function __construct(BasketItem $model = null)
    {
        if ($model === null) {
            $model = new BasketItem();
        }
        parent::__construct($model);
    }


    public function isFree() {
        return $this->getModel()->free;
    }
    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub

        if (array_key_exists('basketId', $conditions) && is_numeric($conditions['basketId'])) {
            $builder->where('basket_id', '=', $conditions['basketId']);
        }

        if (array_key_exists('type', $conditions) && is_string($conditions['type'])) {
            $builder->where('type', '=', $conditions['type']);
        }
        if (array_key_exists('menuItemId', $conditions) && is_numeric($conditions['menuItemId'])) {
            $builder->where('menu_item_id', '=', $conditions['menuItemId']);
        }
        if (array_key_exists('modificationMenuItemId', $conditions) && is_numeric($conditions['modificationMenuItemId'])) {
            $builder->where('modification_menu_item_id', '=', $conditions['modificationMenuItemId']);
        }
        if (array_key_exists('subMenuItemId', $conditions) && is_numeric($conditions['subMenuItemId'])) {
            $builder->where('sub_menu_item_id', '=', $conditions['subMenuItemId']);
        }

        return $builder;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getModel()->id;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->getModel()->quantity;
    }

    /**
     * @return int
     */
    public function getModificationMenuId(): int
    {
        return $this->getModel()->modification_menu_item_id;
    }



    /**
     * @return int
     */
    public function     getPrice() : int
    {
      return  (int)$this->getModel()->price;
    }
}
