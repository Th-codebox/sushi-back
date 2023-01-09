<?php


namespace App\Services\CRM\System;


use App\Repositories\System\BlackListClientRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class BlackListClientService
 * @package App\Services\CRM\System
 * @method BlackListClientRepository getRepository()
 */
class BlackListClientService extends CRMBaseService
{
    public function __construct(?BlackListClientRepository $repository = null)
    {
        parent::__construct($repository);
    }

}
