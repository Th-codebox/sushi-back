<?php


namespace App\Repositories\Store;


use App\Models\Store\Promotion;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method Promotion getModel()
 */
class PromotionRepository extends BaseRepository
{

    protected array $relations = ['promoCode'];
    /**
     * UserRepository constructor.
     * @param Promotion|null $model
     */
    public function __construct(Promotion $model = null)
    {
        if($model === null) {
            $model = new Promotion();
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
        return parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub

    }

}
