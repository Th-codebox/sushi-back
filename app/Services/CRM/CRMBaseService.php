<?php


namespace App\Services\CRM;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class CRMBaseService
 * @property RepositoryInterface $repository
 * @package App\Service\
 */
abstract class CRMBaseService implements CRMServiceInterface
{
    protected ?RepositoryInterface $repository;

    protected array $mapResource = [];


    /**
     * CRMBaseService constructor.
     * @param RepositoryInterface|null $repository
     * @throws CRMServiceException
     */
    public function __construct(?RepositoryInterface $repository = null)
    {
        $this->repository = $repository;
        try {
            if ($this->repository === null) {

                $reflection = new \ReflectionClass(static::class);
                $constructor = $reflection->getConstructor();
                $repositoryConcrete = $constructor->getParameters()[0]->getType()->getName();
                $repositoryReflection = new \ReflectionClass($repositoryConcrete);
                $repositoryConstructor = $repositoryReflection->getConstructor();
                $repositoryDependency = $repositoryConstructor->getParameters()[0]->getType()->getName();
                $this->repository = $repositoryReflection->newInstance(new $repositoryDependency);
            }
        } catch (\ReflectionException $e) {
            throw new CRMServiceException('Невозможно создать сервис ' . get_class($this) . ': ' . $e->getMessage());
        }
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * @param array $data
     * @return array
     */
    protected function dataCorrection(array $data)
    {
        if (array_key_exists('image', $data) && $data['image'] && !Storage::disk('image')->exists($data['image'])) {
            unset($data['image']);
        }
        if (array_key_exists('ico', $data) && $data['ico']  && !Storage::disk('image')->exists($data['ico'])) {
            unset($data['ico']);
        }

        if (array_key_exists('mobileImage', $data) && $data['mobileImage'] && !Storage::disk('image')->exists($data['mobileImage'])) {
            unset($data['mobileImage']);
        }
        if (array_key_exists('desktopImage', $data) && $data['desktopImage'] && !Storage::disk('image')->exists($data['desktopImage'])) {
            unset($data['desktopImage']);
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function events()
    {

    }

    /**
     * @param array $data
     * @return false|mixed|static
     * @throws \App\Repositories\RepositoryException
     */
    public static function add(array $data)
    {
        $newRecord = new static;

        $correctData = $newRecord->dataCorrection($data);

        if ($newRecord->getRepository()->add($correctData)) {
            $newRecord->events();
            return $newRecord;
        }


        return false;
    }


    /**
     * @param array $data
     * @return bool
     * @throws \App\Repositories\RepositoryException
     */
    public function edit(array $data): bool
    {
        $correctData = $this->dataCorrection($data);

        $result = $this->getRepository()->update($correctData);

        $this->events();

        return $result;
    }

    /**
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     */
    public function delete()
    {
        return $this->getRepository()->delete();
    }

    /**
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     */
    public function copy()
    {
        return $this->getRepository()->copy();

    }

    /**
     * @param string $field
     * @return bool
     * @throws \App\Repositories\RepositoryException
     */
    public function switch(string $field): bool
    {
        return $this->getRepository()->switch($field);
    }

    /**
     * @param array $conditions
     * @return CRMServiceInterface
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     */
    public static function findOne(array $conditions = []): CRMServiceInterface
    {

        $repository = (new static)->getRepository()->findOne($conditions);

        return new static($repository);
    }

    /**
     * @param array $conditions
     * @return array
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     */
    public static function findList($conditions = []): array
    {
        $repositories = (new static)->getRepository()->findList($conditions);

        return array_map(static fn(RepositoryInterface $repo): CRMServiceInterface => new static($repo), $repositories);
    }

    /**
     * @param array $params
     * @param int $offset
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \App\Repositories\RepositoryException
     */
    public static function getAllWithPagination(array $params = [], $offset = 0, $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {

        $paginator = (new static)->getRepository()->getAllWithPagination($params, $offset, $perPage);

        return $paginator->setCollection(new Collection(array_map(static fn(RepositoryInterface $repository): Model => $repository->getModel(), $paginator->items())));
    }

    /**
     * @param array $conditions
     * @return int
     */
    public static function count(array $conditions = []): int
    {
        return (new static)->getRepository()->count($conditions);
    }

    /**
     * @return int
     */
    public static function getLastId(): int
    {
        return (new static)->getRepository()->getModel()::latest()->first()->id;
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
    public function findListAsCollectionModel(array $conditions = []): Collection
    {

        $items = $this->repository->findList($conditions);

        return (new Collection(array_map(static fn(RepositoryInterface $repository): Model => $repository->getModel(), $items)));

    }

}
