<?php


namespace App\Services\CRM\System;


use App\Repositories\System\PaymentRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class UserService
 * @package App\Service\System
 *
 * @method PaymentRepository getRepository()
 */
class PaymentService extends CRMBaseService
{
    /**
     * PaymentService constructor.
     * @param PaymentRepository|null $repository
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function __construct(?PaymentRepository $repository = null)
    {
        parent::__construct($repository);
    }


}
