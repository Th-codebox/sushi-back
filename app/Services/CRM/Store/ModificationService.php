<?php


namespace App\Services\CRM\Store;


use App\Enums\ModificationAction;
use App\Enums\ModificationType;
use App\Repositories\Store\ModificationRepository;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;

/**
 * Class ModificationService
 * @package App\Services\CRM\System
 * @method ModificationRepository getRepository()
 */
class ModificationService extends CRMBaseService
{

    public function __construct(?ModificationRepository $repository = null)
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
        if (array_key_exists('action', $data) && $data['action'] === ModificationAction::Add && (!array_key_exists('technicalCardId',$data) && !$this->getRepository()->getModel()->technical_card_id)) {
            throw new CRMServiceException('Должна быть указа тех карта при добавчном методе');
        }


        return $data;
    }
}
