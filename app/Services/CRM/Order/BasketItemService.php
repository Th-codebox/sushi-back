<?php


namespace App\Services\CRM\Order;


use App\Repositories\Order\BasketItemRepository;
use App\Services\CRM\CRMBaseService;

/**
 * Class BasketItemService
 * @package App\Services\CRM\Store
 * @method BasketItemRepository getRepository()
 */
class BasketItemService extends CRMBaseService
{


    public function __construct(?BasketItemRepository $repository = null)
    {

        parent::__construct($repository);

    }


    /**
     * @param array $data
     * @return array
     */
    protected function dataCorrection(array $data): array
    {
        $data = parent::dataCorrection($data); // TODO: Change the autogenerated stub


        return $data;

    }

    public function events()
    {
        $this->getRepository()->getModel()->refresh()->calcTotalPrice();
        $this->getRepository()->update(['price' => $this->getRepository()->getModel()->price]);
    }

}