<?php


namespace App\Services\CRM\System;


use App\Repositories\System\UserDeviceRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class UserDeviceService
 * @package App\Services\CRM\System
 * @method UserDeviceRepository getRepository()
 */
class UserDeviceService extends CRMBaseService
{
    public function __construct(?UserDeviceRepository $repository = null)
    {
        parent::__construct($repository);
    }

}
