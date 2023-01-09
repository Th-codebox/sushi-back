<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\ModificationMenuItemRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class ModificationMenuItemService
 * @package App\Services\CRM\Store
 * @method ModificationMenuItemRepository getRepository()
 */
class ModificationMenuItemService extends CRMBaseService
{

    public function __construct(?ModificationMenuItemRepository $repository = null)
    {
        parent::__construct($repository);
    }

    /**
     * @param array $data
     * @return array
     */
    public function dataCorrection(array $data) : array
    {
        $data =  parent::dataCorrection($data);


        if (array_key_exists('priceAdd', $data) || is_numeric($data['priceAdd'])) {
            $data['priceAdd'] *= 100;
        }


        return $data;
    }
}
