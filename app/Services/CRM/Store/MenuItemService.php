<?php


namespace App\Services\CRM\Store;


use App\Enums\DishType;
use App\Models\Store\MenuItem;
use App\Repositories\Store\MenuItemRepository;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Traits\Slug;

/**
 * Class MenuItemService
 * @package App\Services\CRM\System
 * @method MenuItemRepository getRepository()
 */
class MenuItemService extends CRMBaseService
{
    use Slug;

    public function __construct(?MenuItemRepository $repository = null)
    {
        parent::__construct($repository);
    }


    /**
     * @param array $data
     * @return array
     * @throws CRMServiceException
     */
    public function dataCorrection(array $data): array
    {
        $data = parent::dataCorrection($data);

        if (array_key_exists('slug', $data) || array_key_exists('name', $data)) {
            $data['slug'] = self::generateSlug($data, ((array_key_exists('slug', $data) && is_string($data['slug']) && $data['slug'] !== '') ? $data['slug'] : $data['name']));
        }


        if (array_key_exists('price', $data) && is_numeric($data['price'])) {
            $data['price'] *= 100;
        }

        if (array_key_exists('souseId', $data) && is_numeric($data['souseId'])) {
            try {
                /**
                 * @var MenuItem $souseItem
                 */
                $souseItem = $this::findOne(['id' => $data['souseId']])->getRepository()->getModel();

            } catch (\Throwable  $e) {
                throw new CRMServiceException('Соус не найден');
            }

            if (!$souseItem->technicalCard->dish_type->is(DishType::Souse)) {
                throw new CRMServiceException('Тип блюда должен быть соусом');
            }
        }

        if (array_key_exists('oldPrice', $data) && is_numeric($data['oldPrice'])) {
            $data['oldPrice'] *= 100;
        }

        return $data;
    }

    /**
     * @param array $modifications
     * @return bool
     * @throws \App\Repositories\RepositoryException
     */
    public function saveModifications(array $modifications): bool
    {

        $this->getRepository()->deleteModifications();

        foreach ($modifications as $modification) {
            $this->getRepository()->saveModifications($modification);
        }

        return true;
    }
}
