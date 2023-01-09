<?php


namespace App\Services\CRM\System;


use App\Repositories\System\CallRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class UserService
 * @package App\Service\System
 *
 * @method CallRepository getRepository()
 */
class CallService extends CRMBaseService
{
    /**
     * CallService constructor.
     * @param CallRepository|null $repository
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function __construct(?CallRepository $repository = null)
    {
        parent::__construct($repository);
    }


}
