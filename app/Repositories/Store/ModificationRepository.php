<?php


namespace App\Repositories\Store;


use App\Models\Store\Modification;
use App\Repositories\BaseRepository;

/**
 * Class ModificationRepository
 * @package App\Repositories\Store
 * @method Modification getModel()
 */
class ModificationRepository extends BaseRepository
{

    protected array $relations = ['technicalCard'];

    /**
     * ModificationRepository constructor.
     * @param Modification|null $model
     */
    public function __construct(Modification $model = null)
    {
        if($model === null) {
            $model = new Modification();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

}
