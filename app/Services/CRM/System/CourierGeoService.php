<?php


namespace App\Services\CRM\System;


use App\Repositories\System\CourierGeoRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class UserService
 * @package App\Service\System
 *
 * @method CourierGeoRepository getRepository()
 */
class CourierGeoService extends CRMBaseService
{
    /**
     * CourierGeoService constructor.
     * @param CourierGeoRepository|null $repository
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function __construct(?CourierGeoRepository $repository = null)
    {
        parent::__construct($repository);
    }


}
