<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\ClientPromoCodeRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class ClientPromoCodeService
 * @package App\Services\CRM\System
 * @method ClientPromoCodeRepository getRepository()
 */
class ClientPromoCodeService extends CRMBaseService
{


    public function __construct(?ClientPromoCodeRepository $repository = null)
    {
        parent::__construct($repository);
    }

    public function dataCorrection(array $data)
    {
        $data =  parent::dataCorrection($data);


        return $data;
    }

}
