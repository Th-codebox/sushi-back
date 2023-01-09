<?php


namespace App\Repositories;


use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface RepositoryInterface
{

    /**
     * @return Model
     */
    public function getModel();

    /**
     * @param array $data
     * @return mixed
     * @throws RepositoryException
     */
    public function add(array $data);

    /**
     * @param $data
     * @return mixed
     *
     * @throws RepositoryException
     */
    public function update($data) : bool;

    /**
     * @return mixed
     * @throws RepositoryException
     */
    public function delete();
    /**
     * @return mixed
     * @throws RepositoryException
     */
    public function copy();

    /**
     * @param string $field
     * @return mixed
     * @throws RepositoryException
     */
    public function switch(string $field);

    /**
     * @param array $condition
     * @return RepositoryInterface
     * @throws ModelNotFoundException
     * @throws RepositoryException
     */
    public function findOne(array $condition = []);

    /**
     * @param array $condition
     * @return RepositoryInterface[]
     * @throws ModelNotFoundException
     * @throws RepositoryException
     */
    public function findList(array $condition = []);

    /**
     * @param array $condition
     * @return mixed
     */
    public function count(array $condition = []);

    /**
     * @param array $condition
     * @param int $offset
     * @param int $perPage
     * @return LengthAwarePaginatorContract
     * @throws RepositoryException
     */
    public function getAllWithPagination(array $condition = [], $offset = 0, $perPage = 20);



}
