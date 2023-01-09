<?php


namespace App\Services\CRM\System;


use App\Repositories\System\PermissionRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class PermissionService
 * @package App\Services\CRM\System
 * @method PermissionRepository getRepository()
 */
class PermissionService extends CRMBaseService
{
    public function __construct(?PermissionRepository $repository = null)
    {
        parent::__construct($repository);
    }

}
