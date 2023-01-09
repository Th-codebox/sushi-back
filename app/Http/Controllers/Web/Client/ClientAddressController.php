<?php


namespace App\Http\Controllers\Web\Client;


use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\AddAddress;
use App\Http\Requests\Web\Client\EditAddress;
use App\Http\Resources\Web\ClientAddressResource;
use App\Http\Resources\Web\PolygonResource;
use App\Services\Web\CatalogServiceException;
use App\Services\Web\Client\ClientAddressService;


class ClientAddressController extends Controller
{
    private ClientAddressService $clientAddressService;

    public function __construct(ClientAddressService $clientAddressService)
    {
        $this->clientAddressService = $clientAddressService;
        $this->resource = ClientAddressResource::class;
    }

    public function getClientAddress()
    {
        $collections = $this->clientAddressService->getAllClientAddress(auth()->user()->id);

        return $this->respondWithCollection($collections);
    }

    /**
     * @param AddAddress $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function addAddress(AddAddress $request)
    {
        $data = $request->all();
        $data['city'] = $request->input('city','г. Санкт-петербург');

        try {
            $this->clientAddressService->checkExistClientAddress(auth()->user()->id, $data);

            return $this->responseError('Такой адрес уже существует', 422);

        } catch (\Throwable $e) {
            try {
                $newClientAddressModel = $this->clientAddressService->addClientAddress(auth()->user()->id, $data);

            } catch (CatalogServiceException $e) {
                return $this->responseError($e->getMessage(), 422);
            }
        }

        return $this->respondWithItem($newClientAddressModel);
    }

    /**
     * @param int $id
     * @param EditAddress $request
     * @return mixed
     * @throws CatalogServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function editAddress(int $id, EditAddress $request)
    {
        $data = $request->all();

        $data['city'] = $request->input('city','г. Санкт-петербург');
        $ClientAddressModel = $this->clientAddressService->editAddress(auth()->user()->id, $id, $data);

        return $this->respondWithItem($ClientAddressModel);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function deleteAddress(int $id)
    {
        $this->clientAddressService->deleteAddress(auth()->user()->id, $id);

        return $this->responseSuccess(['success' => true, 'message' => 'Адрес успешно удалён']);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPolygonsHavingPointAddress(int $id)
    {
        $polygons = $this->clientAddressService->getAvailablePolygons($id);

        $this->resource = PolygonResource::class;

        return $this->respondWithCollection($polygons);
    }
}
