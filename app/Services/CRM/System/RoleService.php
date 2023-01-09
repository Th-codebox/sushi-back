<?php


namespace App\Services\CRM\System;


use App\Repositories\System\RoleRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class UserService
 * @package App\Service\System
 *
 * @method RoleRepository getRepository()
 */
class RoleService extends CRMBaseService
{
    /**
     * RoleService constructor.
     * @param RoleRepository|null $repository
     * @throws \ReflectionException
     */
    public function __construct(?RoleRepository $repository = null)
    {
        parent::__construct($repository);
    }


}
