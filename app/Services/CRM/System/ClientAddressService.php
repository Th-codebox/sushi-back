<?php


namespace App\Services\CRM\System;


use App\Libraries\DaData;
use App\Repositories\System\ClientAddressRepository;
use App\Repositories\System\PolygonRepository;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;
use  App\Services\Geo\PolygonService;


/**
 * Class ClientAddressService
 * @package App\Services\CRM\System
 * @method ClientAddressRepository getRepository()
 */
class ClientAddressService extends CRMBaseService
{

    public function __construct(?ClientAddressRepository $repository = null)
    {
        parent::__construct($repository);

    }

    /**
     * @param array $data
     * @return array
     * @throws CRMServiceException
     */
    protected function dataCorrection(array $data): array
    {

        $cities = [
            'г.Санкт-Петербург',
            'г.Мурино',
        ];

        $addressCorrect = false;

        foreach ($cities as $city) {
            $address = 'Россия, Ленинградская обл.,' . $city . ', ' . ($data['street'] ?? $this->getRepository()->getModel()->street) . ', д. ' . ($data['house'] ?? $this->getRepository()->getModel()->house);

            $geoInfo = (new DaData())->getInfoByAddressString($address);

            if (in_array($geoInfo->qc_geo, [0, 1], true)) {
                $data['latGeo'] = $geoInfo->geo_lat;
                $data['letGeo'] = $geoInfo->geo_lon;
                $addressCorrect = true;
                break;
            }
        }

        if (!$addressCorrect) {
            $data['breakAddress'] = '1';
            $data['latGeo'] = '60.035594';
            $data['letGeo'] = '30.374645';
        }

        return parent::dataCorrection($data); // TODO: Change the autogenerated stub
    }


}
