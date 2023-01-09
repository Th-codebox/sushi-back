<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\FilialRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class FilialService
 * @package App\Services\CRM\System
 * @method FilialRepository getRepository()
 */
class FilialService extends CRMBaseService
{

    public function __construct(?FilialRepository $repository = null)
    {
        parent::__construct($repository);
    }

}
