<?php

namespace App\Repositories;

use App\Libraries\Helpers\StringHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository implements RepositoryInterface
{
    private Model $model;

    protected array $relations = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @return $this|false|mixed
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function add(array $data)
    {
        if ($this->handleModification($data)) {
            return $this;
        }
        return false;
    }

    /**
     * @param array $data
     */
    abstract protected function afterModification(array $data = []): void;

    /**
     * @param $data
     * @return bool
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function update($data): bool
    {
        return $this->handleModification($data);
    }

    /**
     * @param $data
     * @return bool
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function handleModification($data)
    {
        try {

            DB::beginTransaction();


            $this->model = $this->getModel()->fill(StringHelper::arrayKeysToSnakeCase($data));

            if ($this->getModel()->saveOrFail()) {

                DB::commit();

                $this->afterModification($data);

                /**
                 * @TODO DELETE ON PROD AND CREATE AUTO CLEAR CACHE FOR ENTITY
                 */
                Artisan::call('clear:app');

                return true;

            }
        } catch (\Throwable $e) {
            throw new RepositoryException($e->getMessage() . ' -  Line : ' . $e->getLine() . ' ;  File : ' . $e->getFile());
        }


        DB::rollBack();


        return false;
    }

    /**
     * @return bool|mixed|null
     * @throws RepositoryException
     */
    public function delete()
    {
        try {
            $result = $this->getModel()->delete();

            /**
             * @TODO DELETE ON PROD AND CREATE AUTO CLEAR CACHE FOR ENTITY
             */
            Artisan::call('clear:app');

            return $result;
        } catch (\Throwable $e) {
            throw new RepositoryException($e->getMessage() . ' -  Line : ' . $e->getLine());
        }
    }

    /**
     * @return bool|mixed|null
     * @throws RepositoryException
     */
    public function copy()
    {

        $newModel = $this->getModel()->replicate();

        $newModel->push();




        Artisan::call('clear:app');

        return (new static($newModel));
    }

    /**
     * @param string $field
     * @return bool|mixed
     * @throws RepositoryException
     */
    public function switch(string $field)
    {
        try {
            return $this->update([
                $field => !$this->getModel()->{$field},
            ]);
        } catch (\Throwable $e) {
            throw new RepositoryException($e->getMessage() . ' -  Line : ' . $e->getLine());
        }
    }

    /**
     * @param array $conditions
     * @return $this|RepositoryInterface
     * @throws RepositoryException
     */
    public function findOne(array $conditions = [])
    {

        return new static($this->conditionsBuilder($conditions)->firstOrFail());
    }

    /**
     * @param array $conditions
     * @return array
     * @throws RepositoryException
     */
    public function findList(array $conditions = []) : array
    {
        $query = $this->conditionsBuilder($conditions);

        if (array_key_exists('limit', $conditions) && is_numeric($conditions['limit'])) {
            $query->limit((int)$conditions['limit']);
        }

        return $query->get()->map(function ($item) {
            return new static($item);
        })->toArray();
    }

    /**
     * @param array $conditions
     * @return int
     * @throws RepositoryException
     */
    public function count(array $conditions = []): int
    {
        $query = $this->conditionsBuilder($conditions);

        return $query->get()->count();
    }

    /**
     * @param array $params
     * @param int $offset
     * @param int $perPage
     * @return LengthAwarePaginatorContract
     * @throws RepositoryException
     */
    public function getAllWithPagination(array $params = [], $offset = 0, $perPage = 20): LengthAwarePaginatorContract
    {
        if ((int)$perPage > 0) {
            $perPage = (int)$perPage;
        } else {
            $perPage = 20;
        }

        $query = $this->conditionsBuilder($params['conditions']);

        $query = $this->search($query, $params['search']);



        $total = $query->toBase()->getCountForPagination();

        $query = $this->sort($query, $params['sort']);

        if (array_key_exists('sort', $params) && is_array($params['sort']) && $params['sort']['sortField'] === null) {
            if (in_array('sort_order', $this->getModel()->getFillable())) {
                $query->orderBy('sort_order', 'ASC');
                if (in_array('name', $this->getModel()->getFillable())) {
                    $query->orderBy('name', 'ASC');
                }
            } elseif (in_array('created_at', $this->getModel()->getDates())) {
                $query->orderBy('created_at', 'DESC');
            }
        }



        $results = ($total > 0) ? $query->offset($offset)->take($perPage)->get() : $query->getModel()->newCollection();

        $paginator = new LengthAwarePaginator($results, $total, $perPage);

        return $paginator->setCollection(new Collection(array_map(fn(Model $model): RepositoryInterface => new static($model), $paginator->items())));
    }

    public function search(Builder $query, array $searchArray = []): Builder
    {
        if (array_key_exists('search', $searchArray)
            && is_string($searchArray['search'])
            && array_key_exists('text', $searchArray)
            && is_string($searchArray['text'])
            && in_array($searchArray['search'], $this->model->getFillable(), true)) {

            $query->where($searchArray['search'], 'like', "%" . $searchArray['text'] . "%");
        }

        return $query;
    }

    public function sort(Builder $query, array $sortArray = []): Builder
    {
        if (array_key_exists('sortMethod', $sortArray)
            && is_string($sortArray['sortMethod'])
            && array_key_exists('sortField', $sortArray)
            && is_string($sortArray['sortField'])
            && (in_array($sortArray['sortField'], $this->model->getFillable(), true) || ($sortArray['sortField'] === 'id'))) {

            $query->orderBy($sortArray['sortField'], $sortArray['sortMethod']);
        }

        return $query;
    }

    /**
     * @param array $conditions
     * @param array $searchArray
     * @return Builder
     * @throws RepositoryException
     */
    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $modelInstance = $this->getModel();

        $query = $modelInstance->newQuery();

        if (array_key_exists('relations', $conditions)) {

            if (is_array($conditions['relations']) && count($conditions['relations']) > 0) {
                $this->relations = $conditions['relations'];

            } elseif (is_string($conditions['relations']) && $conditions['relations'] !== '') {
                $this->relations = explode(',', $conditions['relations']);
            } elseif (!$conditions['relations']) {
                $this->relations = [];
            }

            if ($conditions['relations'] == 'null') {
                $this->relations = [];
            }
        }


        if (is_array($this->relations) && count($this->relations) > 0) {
            $query->with($this->relations);
        }

        if (array_key_exists('!id', $conditions)) {
            if (is_array($conditions['!id'])) {
                $query->where('id', 'NOT IN', $conditions['!id']);
            } else {
                $query->where('id', '!=', $conditions['!id']);
            }
        }
        if (array_key_exists('id', $conditions)) {
            if (is_array($conditions['id'])) {
                $query->whereIn('id', $conditions['id']);
            } else {
                $query->where('id', '=', $conditions['id']);
            }
        }


        if (array_key_exists('name', $conditions) && is_string($conditions['name'])) {
            $query->where('name', '=', $conditions['name']);
        }


        if (array_key_exists('!name', $conditions) && is_array($conditions['!name'])) {
            $query->whereNotIn('name', $conditions['!name']);
        }


        if (array_key_exists('slug', $conditions) && is_string($conditions['slug'])) {
            $query->where('slug', '=', $conditions['slug']);
        }

        if (array_key_exists('status', $conditions) && !is_array($conditions['status'])) {
            $query->where('status', '=', $conditions['status']);
        }

        if (array_key_exists('sortId', $conditions) && !is_array($conditions['sortId'])) {
            $query->orderBy('id', $conditions['sortId']);
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'new') {
            $query->orderBy('id', 'DESC');
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'old') {
            $query->orderBy('id', 'ASC');
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'sort_order') {
            $query->orderBy('sort_order', 'DESC');
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'name') {
            $query->orderBy('name', 'DESC');
        }

        return $query;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->getModel()->toArray();
    }

    /**
     * @param array $repoCollections
     * @return Collection
     */
    public function getModelCollections(array $repoCollections): Collection
    {
        return (new Collection(array_map(fn(RepositoryInterface $repository): Model => $repository->getModel(), $repoCollections)));
    }
}

