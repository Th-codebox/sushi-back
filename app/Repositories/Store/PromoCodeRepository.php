<?php


namespace App\Repositories\Store;


use App\Models\Store\Collection;
use App\Models\Store\PromoCode;
use App\Models\Store\MenuItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method PromoCode getModel()
 */
class PromoCodeRepository extends BaseRepository
{

    protected array $relations = ['saleMenuItem','saleModificationMenuItem'];
    /**
     * UserRepository constructor.
     * @param PromoCode|null $model
     */
    public function __construct(PromoCode $model = null)
    {
        if($model === null) {
            $model = new PromoCode();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

    /**
     * @param array $conditions
     * @return Builder
     * @throws \App\Repositories\RepositoryException
     */
    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions);

        if (array_key_exists('type', $conditions) && is_string($conditions['type'])) {
            $builder->where('type', '=', $conditions['type']);
        }

        if (array_key_exists('action', $conditions) && is_string($conditions['action'])) {
            $builder->where('action', '=', $conditions['action']);
        }

        if (array_key_exists('referrerClientId', $conditions) && $conditions['referrerClientId']) {
            $builder->where('referrer_client_id', '=', $conditions['referrerClientId']);
        }
        if (array_key_exists('code', $conditions) && is_string($conditions['code'])) {
            $builder->where('code', '=', $conditions['code']);
        }
        if (array_key_exists('findName', $conditions) && is_string($conditions['findName'])) {
            $builder->where('name', 'like', "%" . $conditions['findName'] . "%");
        }
        if (array_key_exists('findCode', $conditions) && is_string($conditions['findCode'])) {
            $builder->where('code', 'like',"%" . $conditions['findCode'] . "%");
        }
        if (array_key_exists('noFriendGift', $conditions) && $conditions['noFriendGift']) {
            $builder->whereNull('referrer_client_id');
        }

        return $builder;

    }

}
