<?php


namespace App\Services\CRM;


use App\Repositories\RepositoryInterface;

interface CRMServiceInterface
{
    /**
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @param array $data
     * @return mixed
     */
    public static function add(array $data);

    /**
     * @param array $data
     * @return bool
     */
    public function edit(array $data): bool;

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return mixed
     */
    public function copy();

    /**
     * @param string $field
     * @return mixed
     */
    public function switch(string $field);

    /**
     * @param array $conditions
     * @return mixed
     */
    public function findListAsCollectionModel(array $conditions);

}
