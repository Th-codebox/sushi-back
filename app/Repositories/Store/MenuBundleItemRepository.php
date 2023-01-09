<?php


namespace App\Repositories\Store;



use App\Models\Store\MenuBundleItem;

use App\Repositories\BaseRepository;
use App\Repositories\RepositoryException;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method MenuBundleItem getModel()
 */
class MenuBundleItemRepository extends BaseRepository
{

    protected array $relations = [];

    /**
     * MenuBundleItemRepository constructor.
     * @param MenuBundleItem|null $model
     */
    public function __construct(MenuBundleItem $model = null)
    {
        if ($model === null) {
            $model = new MenuBundleItem();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @throws RepositoryException
     */
    protected function afterModification(array $data = []): void
    {


    }


    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions);

        if (array_key_exists('menuItemId', $conditions) && is_numeric($conditions['menuItemId'])) {
            $builder->where('menu_item_id', '=', $conditions['menuItemId']);
        }

        if (array_key_exists('menuItemBundleId', $conditions) && is_numeric($conditions['menuItemBundleId'])) {
            $builder->where('menu_item_bundle_id', '=', $conditions['menuItemBundleId']);
        }

        return $builder;
    }
}
