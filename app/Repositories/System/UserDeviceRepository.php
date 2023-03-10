<?php


namespace App\Repositories\System;

use App\Models\System\UserDevice as UserDeviceModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method UserDeviceModel getModel()
 */
class UserDeviceRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     * @param UserDeviceModel|null $model
     */
    public function __construct(UserDeviceModel $model = null)
    {
        if ($model === null) {
            $model = new UserDeviceModel();
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

        if (array_key_exists('agent', $conditions) && !is_array($conditions['agent'])) {
            $builder->where('agent', '=', $conditions['agent']);
        }

        if (array_key_exists('device', $conditions) && !is_array($conditions['device'])) {
            $builder->where('device', '=', $conditions['device']);
        }

        if (array_key_exists('userId', $conditions) && is_numeric($conditions['userId'])) {
            $builder->where('user_id', '=', $conditions['userId']);
        }


        if (array_key_exists('deviceId', $conditions) && !is_array($conditions['deviceId'])) {
            $builder->where('device_id', '=', $conditions['deviceId']);
        }


        if (array_key_exists('logoutAt', $conditions)) {

            $builder->where('logout_at', '=', $conditions['logoutAt']);
        }

        return $builder;
    }

}
