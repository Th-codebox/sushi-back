<?php


namespace App\Repositories\Store;


use App\Enums\CollectionType;
use App\Models\Store\Collection;
use App\Models\Store\MenuItem;
use App\Repositories\BaseRepository;
use App\Services\CRM\Store\CollectionTypeService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method Collection getModel()
 */
class CollectionRepository extends BaseRepository
{

    protected array $relations = ['menuItems', 'category', 'types'];

    /**
     * CollectionRepository constructor.
     * @param Collection|null $model
     */
    public function __construct(Collection $model = null)
    {
        if ($model === null) {
            $model = new Collection();
        }
        parent::__construct($model);
    }

    protected function afterModification(array $data = []): void
    {
        if (array_key_exists('menuItems', $data) && is_array($data['menuItems'])) {
            $this->saveMenuItems($data['menuItems']);
        }

        if (array_key_exists('types', $data) && is_array($data['types'])) {
            $this->saveTypes($data['types']);
        }
    }

    /**
     * @param array $menuItems
     */
    private function saveMenuItems(array $menuItems)
    {
        $this->getModel()->menuItems()->detach();

        foreach ($menuItems as $product) {
            if (is_array($product) && array_key_exists('id', $product) && is_numeric($product['id']) && (int)$product['id'] > 0) {
                $this->getModel()->menuItems()->attach($product['id']);
            } elseif (is_numeric($product) && (int)$product > 0) {
                $this->getModel()->menuItems()->attach($product);
            }
        }
    }

    /**
     * @param array $types
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    private function saveTypes(array $types)
    {
        $collectionTypes = CollectionTypeService::findList(['collectionId' => $this->getModel()->id]);

        foreach ($collectionTypes as $collectionType) {
            /**
             * @var CollectionTypeRepository $collectionType
             *
             */
            $collectionType->delete();
        }

        foreach ($types as $type) {

            if (CollectionType::hasValue($type)) {

                CollectionTypeService::add([
                    'collectionId' => $this->getModel()->id,
                    'value'        => $type,
                ]);
            }

        }
    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {

        if (array_key_exists('noShowHideElement', $conditions) && $conditions['noShowHideElement'] === true) {
            $this->relations = [
                'menuItems' =>  function ( $query) {

                    $query->where('hide', '=', 0);
                },
                'category',
                'types'
            ];
        }

        $builder = parent::conditionsBuilder($conditions);

        if (array_key_exists('type', $conditions) && is_string($conditions['type'])) {

            $builder->whereHas('types', function(Builder $query)use ($conditions){
                $query->where('value', '=', $conditions['type']);
            });
        }

        if (array_key_exists('target', $conditions) && is_string($conditions['target'])) {
            $builder->where('target', '=', $conditions['target']);
        }

        return $builder;
    }
}
