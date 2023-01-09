<?php


namespace App\Services\Web\Client;


use App\Libraries\DaData;

use App\Models\System\ClientAddress;
use App\Repositories\Client\ClientAddressRepository;
use App\Services\CRM\CRMServiceException;
use App\Services\Geo\PolygonService;
use App\Services\Web\CatalogServiceException;

class ClientAddressService
{
    private ClientAddressRepository $clientAddressRepo;
    private DaData $daData;
    private PolygonService $polygon;

    public function __construct(ClientAddressRepository $clientAddressRepo, DaData $daData, PolygonService $polygon)
    {
        $this->clientAddressRepo = $clientAddressRepo;
        $this->daData = $daData;
        $this->polygon = $polygon;
    }

    /**
     * @param int $clientAddressId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailablePolygons(int $clientAddressId)
    {
        $clientAddress = $this->clientAddressRepo->getClientAddressById($clientAddressId);

        return $this->polygon->checkAndGetAvailablePolygonsByPoint($clientAddress->lat_geo,$clientAddress->let_geo);
    }


    /**
     * @param int $clientAddressId
     * @return \Illuminate\Support\Collection
     */
    public function getAllClientAddress(int $clientAddressId)
    {
        return $this->clientAddressRepo->getAllAddressByClientId($clientAddressId);
    }

    /**
     * @param int $clientId
     * @param array $data
     * @return ClientAddress
     */
    public function checkExistClientAddress(int $clientId, array $data)
    {
        return $this->clientAddressRepo->findAddressByClientIdAndFields($clientId, $data);
    }

    /**
     * @param int $clientId
     * @param array $data
     * @return ClientAddress
     * @throws CRMServiceException
     * @throws CatalogServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function addClientAddress(int $clientId, array $data): ClientAddress
    {
        $newAddressModel = new ClientAddress();

        $newAddressModel->client_id = $clientId;
        $newAddressModel->city = $data['city'];
        $newAddressModel->street = $data['street'];
        $newAddressModel->house = $data['house'];
        $newAddressModel->entry = $data['entry'] ?? null;
        $newAddressModel->floor = $data['floor'] ?? null;
        $newAddressModel->apartment_number = $data['apartmentNumber'] ?? null;
        $newAddressModel->name = $data['name'] ?? null;
        $newAddressModel->ico_name = $data['icoName'] ?? null;

        $geoInfo = $this->daData->getInfoByAddressString($newAddressModel->getFullAddress());

        if (in_array($geoInfo->qc_geo, [0, 1])) {
            $newAddressModel->let_geo = $geoInfo->geo_lon;
            $newAddressModel->lat_geo = $geoInfo->geo_lat;
        } else {
            $newAddressModel->let_geo = '30.374645';
            $newAddressModel->lat_geo = '60.035594';
            $newAddressModel->break_address = 1;
            $geoInfo->geo_lat = '60.035594';
            $geoInfo->geo_lon = '30.374645';
        }

        if($this->polygon->checkAndGetAvailablePolygonsByPoint($geoInfo->geo_lat,$geoInfo->geo_lon)->isEmpty()) {
            $newAddressModel->break_address = 1;
            $newAddressModel->let_geo = '30.374645';
            $newAddressModel->lat_geo = '60.035594';
            //throw new CRMServiceException('По данному адресу доставка не возможна');
        }

        $this->clientAddressRepo->saveAddress($newAddressModel);

        return $newAddressModel;
    }

    /**
     * @param int $clientId
     * @param int $clientAddressId
     * @param array $data
     * @return ClientAddress
     * @throws CatalogServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function editAddress(int $clientId, int $clientAddressId, array $data): ClientAddress
    {
        $addressModel = $this->clientAddressRepo->getClientAddressByIdAndClientId($clientAddressId,$clientId);

        $addressModel->city = $data['city'] ?? $addressModel->city;
        $addressModel->street = $data['street'] ?? $addressModel->street;
        $addressModel->house = $data['house'] ?? $addressModel->house;
        $addressModel->entry = $data['entry'] ?? $addressModel->entry;
        $addressModel->floor = $data['floor'] ?? $addressModel->floor;
        $addressModel->apartment_number = $data['apartmentNumber'] ?? $addressModel->apartment_number;
        $addressModel->name = $data['name'] ?? $addressModel->name;
        $addressModel->ico_name = $data['icoName'] ?? $addressModel->ico_name;

        $geoInfo = $this->daData->getInfoByAddressString($addressModel->getFullAddress());

        if (in_array($geoInfo->qc_geo, [0, 1])) {
            $addressModel->let_geo = $geoInfo->geo_lon;
            $addressModel->lat_geo = $geoInfo->geo_lat;
        } else {
            $addressModel->let_geo = '30.374645';
            $addressModel->lat_geo = '60.035594';
            $addressModel->break_address = 1;
            $geoInfo->geo_lat = '60.035594';
            $geoInfo->geo_lon = '30.374645';
        }

        if($this->polygon->checkAndGetAvailablePolygonsByPoint($geoInfo->geo_lat,$geoInfo->geo_lon)->isEmpty()) {
            $addressModel->break_address = 1;
            $addressModel->let_geo = '30.374645';
            $addressModel->lat_geo = '60.035594';
            //throw new CRMServiceException('По данному адресу доставка не возможна');
        }

        $this->clientAddressRepo->saveAddress($addressModel);

        return $addressModel;
    }

    /**
     * @param int $clientId
     * @param int $clientAddressId
     * @return bool
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function deleteAddress(int $clientId, int $clientAddressId): bool
    {
        $addressModel = $this->clientAddressRepo->getClientAddressByIdAndClientId($clientAddressId,$clientId);

        $this->clientAddressRepo->deleteAddress($addressModel);

        return true;
    }
}
