<?php


namespace App\Repositories\Client;


use App\Models\System\ClientAddress;
use App\Repositories\RepositoryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ClientAddressRepository
 * @package App\Repositories\Client
 */
class ClientAddressRepository
{
    private ClientAddress $clientAddressModel;

    /**
     * ClientAddressRepository constructor.
     * @param ClientAddress $clientAddressModel
     */
    public function __construct(ClientAddress $clientAddressModel)
    {
        $this->clientAddressModel = $clientAddressModel;
    }

    /**
     * @param int $clientId
     * @return Collection
     */
    public function getAllAddressByClientId(int $clientId): Collection
    {
        return $this->clientAddressModel::where('client_id', '=', $clientId)->get();
    }


    /**
     * @param int $clientId
     * @return ClientAddress
     */
    public function getClientAddressById(int $clientId): ClientAddress
    {
        return $this->clientAddressModel::where('id', '=', $clientId)->firstOrfail();
    }


    /**
     * @param int $id
     * @param int $clientId
     * @return ClientAddress
     */
    public function getClientAddressByIdAndClientId(int $id,int $clientId): ClientAddress
    {
        return $this->clientAddressModel::where('id', '=', $id)->where('client_id', '=', $clientId)->firstOrfail();
    }


    /**
     * @param int $clientId
     * @param array $data
     * @return ClientAddress
     */
    public function findAddressByClientIdAndFields(int $clientId, array $data): ClientAddress
    {
        return $this->clientAddressModel->where('client_id', '=', $clientId)
            ->where('city', '=', $data['city'])
            ->where('street', '=', $data['street'])
            ->where('house', '=', $data['house'])
            ->firstOrFail();
    }

    /**
     * @param ClientAddress $model
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function saveAddress(ClientAddress $model)
    {
        try {
            DB::beginTransaction();

            $model->saveOrFail();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new RepositoryException($e->getMessage() . ' -  Line : ' . $e->getLine());
        }
    }
    /**
     * @param ClientAddress $model
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function deleteAddress(ClientAddress $model)
    {
        try {
            DB::beginTransaction();

            $model->delete();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new RepositoryException($e->getMessage() . ' -  Line : ' . $e->getLine());
        }
    }

}
