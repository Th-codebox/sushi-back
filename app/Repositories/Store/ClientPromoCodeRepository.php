<?php


namespace App\Repositories\Store;


use App\Models\Store\Collection;
use App\Models\Store\ClientPromoCode;
use App\Models\Store\MenuItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method ClientPromoCode getModel()
 */
class ClientPromoCodeRepository extends BaseRepository
{

    protected array $relations = ['promoCode','order'];
    /**
     * UserRepository constructor.
     * @param ClientPromoCode|null $model
     */
    public function __construct(ClientPromoCode $model = null)
    {
        if($model === null) {
            $model = new ClientPromoCode();
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

        if (array_key_exists('clientId', $conditions) && is_numeric($conditions['clientId'])) {
            $builder->where('client_id', '=', $conditions['clientId']);
        }
        if (array_key_exists('orderId', $conditions) && !is_array($conditions['orderId'])) {
            $builder->where('order_id', '=', $conditions['orderId']);
        }
        if (array_key_exists('!orderId', $conditions) && !is_array($conditions['!orderId'])) {
            $builder->where('order_id', '!=', $conditions['!orderId']);
        }

        if (array_key_exists('promoCodeId', $conditions) && is_numeric($conditions['promoCodeId'])) {
            $builder->where('promo_code_id', '=', $conditions['promoCodeId']);
        }
        if (array_key_exists('activated', $conditions) && is_bool($conditions['activated'])) {
            $builder->where('activated', '=', $conditions['activated']);
        }

        if (array_key_exists('deadLine', $conditions) && $conditions['deadLine']) {
            $builder->whereDate('dead_line', '<', Carbon::now()->toDateString());
        }
        if (array_key_exists('dateBegin', $conditions) && $conditions['dateBegin']) {
            $builder->whereDate('date_begin', '<', Carbon::now()->toDateString());
        }

        return $builder;

    }

}
