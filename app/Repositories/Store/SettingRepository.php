<?php

namespace App\Repositories\Store;

use App\Models\Store\Setting as SettingModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SettingRepository
 * @package App\Repositories\Store
 * @method SettingModel getModel()
 */
class SettingRepository extends BaseRepository
{

    protected array $relations = ['valueFilials'];

    /**
     * UserRepository constructor.
     * @param SettingModel|null $model
     */
    public function __construct(SettingModel $model = null)
    {
        if ($model === null) {
            $model = new SettingModel();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {

    }


    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub


        if (array_key_exists('key', $conditions) && is_string($conditions['key']) && $conditions['key']) {
            $builder->where('key', $conditions['key']);
        }

        if (array_key_exists('name', $conditions) && is_string($conditions['name']) && $conditions['name']) {
            $builder->where('name', $conditions['name']);
        }

        return $builder;
    }

    /**
     * @param int|null $filialId
     * @return array
     */
    public function formatSetting(?int $filialId = null) : array
    {

        $value =   $this->getModel()->json ? json_decode( $this->getModel()->value,1) :  $this->getModel()->value;

        $filialValuesArray = $this->getModel()->valueFilials;

        foreach ($filialValuesArray as $item) {
            if($filialId === $item['filial_id'] && $item['value']) {
                $value =  $item['json'] ? json_decode($item['value'],1) : $item['value'];
            }
        }

        return [
            'id'    => $this->getModel()->id,
            'key'   => $this->getModel()->key,
            'name'  => $this->getModel()->name,
            'group' => $this->getModel()->group,
            'value' => $value,
        ];
    }

}