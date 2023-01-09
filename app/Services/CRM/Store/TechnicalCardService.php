<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\TechnicalCardRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class TechnicalCardService
 * @package App\Services\CRM\System
 * @method TechnicalCardRepository getRepository()
 */
class TechnicalCardService extends CRMBaseService
{

    public function __construct(?TechnicalCardRepository $repository = null)
    {
        parent::__construct($repository);
    }
}
