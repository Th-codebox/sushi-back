<?php


namespace App\Repositories\System;


use App\Models\System\UserDoc as UserDoc;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryException;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method UserDoc getModel()
 */
class UserDocRepository extends BaseRepository
{
    protected array $relations = ['filials', 'role'];

    /**
     * UserRepository constructor.
     * @param UserDoc|null $model
     */
    public function __construct(UserDoc $model = null)
    {

        if ($model === null) {
            $model = new UserDoc();
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
        $query = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub



        return $query;
    }


}
