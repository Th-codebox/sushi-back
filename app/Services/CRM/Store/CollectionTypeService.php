<?php


namespace App\Services\CRM\Store;



use App\Repositories\Store\CollectionTypeRepository;
use App\Services\CRM\CRMBaseService;


/**
 * Class CollectionTypeService
 * @package App\Services\CRM\System
 * @method CollectionTypeRepository getRepository()
 */
class CollectionTypeService extends CRMBaseService
{


    public function __construct(?CollectionTypeRepository $repository = null)
    {
        parent::__construct($repository);
    }

    public function dataCorrection(array $data)
    {
        $data =  parent::dataCorrection($data);


        return $data;
    }
}
