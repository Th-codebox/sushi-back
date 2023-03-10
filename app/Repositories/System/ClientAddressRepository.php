<?php


namespace App\Repositories\System;

use App\Models\System\ClientAddress as ClientAddressModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ClientAddressRepository
 * @package App\Repositories\System
 * @method ClientAddressModel getModel()
 */
class ClientAddressRepository extends BaseRepository
{

    protected array $relations = ['client'];

    /**
     * UserRepository constructor.
     * @param ClientAddressModel|null $model
     */
    public function __construct(ClientAddressModel $model = null)
    {
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
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub


        if (array_key_exists('clientId', $conditions) && is_numeric($conditions['clientId'])) {
            $builder->where('client_id', '=', $conditions['clientId']);
        }


        return $builder;
    }

    public function getFullAddress() {
        return $this->getModel()->getFullAddress();
    }
}
