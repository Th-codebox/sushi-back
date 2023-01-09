<?php


namespace App\Repositories\Store;


use App\Models\Store\TechnicalCard;
use App\Repositories\BaseRepository;
/**
 * Class TechnicalCardRepository
 * @package App\Repositories\Store
 * @method TechnicalCard getModel()
 */
class TechnicalCardRepository extends BaseRepository
{

    protected array $relations = ['menuItems'];

    /**
     * TechnicalCardRepository constructor.
     * @param TechnicalCard|null $model
     */
    public function __construct(TechnicalCard $model = null)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

}
