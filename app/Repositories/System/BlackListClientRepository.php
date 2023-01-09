<?php


namespace App\Repositories\System;

use App\Models\System\BlackListClient as BlackListClientModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method BlackListClientModel getModel()
 */
class BlackListClientRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     * @param BlackListClientModel|null $model
     */
    public function __construct(BlackListClientModel $model = null)
    {
        if ($model === null) {
            $model = new BlackListClientModel();
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
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub


        if (array_key_exists('clientId', $conditions) && is_numeric($conditions['clientId'])) {
            $builder->where('client_id', '=', $conditions['clientId']);
        }


        return $builder;
    }

}