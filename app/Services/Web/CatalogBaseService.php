<?php


namespace App\Services\Web;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class CatalogBaseService implements CatalogServiceInterface
{
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $conditions
     * @return Model
     * @throws \App\Repositories\RepositoryException
     */
    public function getItemByConditions(array $conditions = []): Model
    {

        $item = $this->repository->findOne($conditions);

        return $item->getModel();
    }

    /**
     * @param array $conditions
     * @return Collection
     * @throws \App\Repositories\RepositoryException
     */
    public function getItemsByConditions(array $conditions = []): Collection
    {

        $items = $this->repository->findList($conditions);

        return (new Collection(array_map(fn(RepositoryInterface $repository): Model => $repository->getModel(), $items)));

    }

    /**
     * @param array $params
     * @param int $offset
     * @param int $perPage
     * @return LengthAwarePaginatorContract
     * @throws \App\Repositories\RepositoryException
     */
    public function getItemsWithPagination(array $params = [], $offset = 0, $perPage = 20)
    {
        $paginationContract = $this->repository->getAllWithPagination($params, $offset, $perPage);

        return $paginationContract->setCollection(new Collection(array_map(fn(RepositoryInterface $repository): Model => $repository->getModel(), $paginationContract->items())));

    }


}
