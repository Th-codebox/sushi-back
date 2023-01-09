<?php


namespace App\Services\Web;


use App\Repositories\RepositoryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Model;

interface CatalogServiceInterface
{


    /**
     * @param array $conditions
     * @return mixed
     */
    public function getItemByConditions(array $conditions = []);

    /**
     * @param array $conditions
     * @return mixed
     */
    public function getItemsByConditions(array $conditions = []);

    /**
     * @param array $params
     * @param int $offset
     * @param int $perPage
     * @return mixed
     */
    public function getItemsWithPagination(array $params = [],int $offset = 0,int $perPage = 20);
}
