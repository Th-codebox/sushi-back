<?php


namespace App\Repositories\Store;



use App\Models\Store\CollectionType;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method CollectionType getModel()
 */
class CollectionTypeRepository extends BaseRepository
{

    protected array $relations = [];

    /**
     * CollectionTypeRepository constructor.
     * @param CollectionType|null $model
     */
    public function __construct(CollectionType $model = null)
    {
        if($model === null) {
            $model = new CollectionType();
        }
        parent::__construct($model);
    }

    protected function afterModification(array $data = []): void
    {

    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions);


        if(array_key_exists('collectionId',$conditions) && is_numeric($conditions['collectionId'])) {
            $builder->where('collection_id','=',$conditions['collectionId']);
        }

        if(array_key_exists('value',$conditions) && is_string($conditions['value'])) {
            $builder->where('value','=',$conditions['value']);
        }

        return $builder;
    }
}
