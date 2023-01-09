<?php


namespace App\Repositories\System;

use App\Models\System\Permission as PermissionModel;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method PermissionModel getModel()
 */
class PermissionRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     * @param PermissionModel|null $model
     */
    public function __construct(PermissionModel $model = null)
    {
        if ($model === null) {
            $model = new PermissionModel();
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
