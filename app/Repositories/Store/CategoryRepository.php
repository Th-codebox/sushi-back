<?php


namespace App\Repositories\Store;


use App\Models\Store\Collection;
use App\Models\Store\Category;
use App\Models\Store\MenuItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method Category getModel()
 */
class CategoryRepository extends BaseRepository
{

    protected array $relations = ['menuItems', 'collections'];

    /**
     * UserRepository constructor.
     * @param Category|null $model
     */
    public function __construct(Category $model = null)
    {
        if ($model === null) {
            $model = new Category();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

    /**
     * @param array $conditions
     * @return Builder
     * @throws \App\Repositories\RepositoryException
     */
    protected function conditionsBuilder(array $conditions = []): Builder
    {
        if (array_key_exists('noShowHideElement', $conditions) && $conditions['noShowHideElement'] === true) {
            $this->relations = [
                'menuItems' =>  function ( $query) {

                    $query->where('hide', '=', 0);
                },
                'collections'
            ];
        }

        return parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub
    }

}