<?php


namespace App\Services\CRM\System;


use App\Repositories\System\ClientDeviceRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class ClientDeviceService
 * @package App\Services\CRM\System
 * @method ClientDeviceRepository getRepository()
 */
class ClientDeviceService extends CRMBaseService
{
    public function __construct(?ClientDeviceRepository $repository = null)
    {
        parent::__construct($repository);
    }

}
