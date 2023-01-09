<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\SliderRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class SliderService
 * @package App\Services\CRM\System
 * @method SliderRepository getRepository()
 */
class SliderService extends CRMBaseService
{


    public function __construct(?SliderRepository $repository = null)
    {
        parent::__construct($repository);
    }

    public function dataCorrection(array $data)
    {
        $data =  parent::dataCorrection($data);

        return $data;
    }

}
