<?php

namespace App\Repositories;

use App\Contracts\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\This;

abstract class EloquentBaseRepository
{
    protected Model $model;

    protected bool $withoutGlobalScopes = false;

    protected array $with = [];


    /**
     * Set the relationships of the query.
     *
     * @param array $with
     * @return $this
     */
    public function with(array $with = [])
    {
        $copy = clone $this;
        $copy->with = $with;
        return $copy;
    }

    /**
     * Set withoutGlobalScopes attribute to true and apply it to the query.
     *
     * @return $this
     */
    protected function withoutGlobalScopes()
    {
        $copy = clone $this;
        $copy->withoutGlobalScopes = true;
        return $copy;
    }

    /**
     * Save a resource.
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        #TODO
    }

    /**
     * Update a resource.
     *
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        return tap($model)->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function findByFilters(): LengthAwarePaginator
    {
        return $this->query()->with($this->with)->paginate();
    }

    /**
     * Find a resource by id.
     *
     * @param string $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneById(string $id): Model
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Find a resource by key value criteria.
     *
     * @param array $criteria
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneBy(array $criteria): Model
    {
        if (!$this->withoutGlobalScopes) {
            return $this->query()->with($this->with)
                ->where($criteria)
                ->orderByDesc('created_at')
                ->firstOrFail();
        }

        return $this->query()->with($this->with)
            ->withoutGlobalScopes()
            ->where($criteria)
            ->orderByDesc('created_at')
            ->firstOrFail();
    }


    /**
     * @return mixed
     */
    protected function query()
    {
        return $this->model->newQuery();
    }
}
