<?php


namespace App\Repositories\Order;


use App\Enums\BasketSource;
use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Models\Order\Order;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


/**
 * Class UserRepository
 * @package App\Repositories\System
 * @method  Order getModel()
 */
class OrderRepository extends BaseRepository
{

    /*protected array $relations = [
        'basket',
        //'basket.clientPromoCode.promoCode',
        'basket.items',
        'basket.items.menuItem.categories',
        'basket.items.menuItem.modifications',
        'clientAddress',
        'client',
        'client.actualClientPromoCodes',
        'courier',
        'manager',
        'filial',
        //'filial.settings',
        //'filial.settings.defaultSeting',
        'payment',
        'activities'
    ];*/

    protected array $relations = ['basket', 'clientAddress', 'client', 'courier', 'manager', 'filial', 'payment'];

    /**
     * OrderRepository constructor.
     * @param Order|null $model
     */
    public function __construct(Order $model = null)
    {
        if ($model === null) {
            $model = new Order();
        }
        parent::__construct($model);
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return (string)$this->getModel()->payment_type;
    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub

        if (!empty($conditions['dateFrom']) || !empty($conditions['dateTo']))
        {
            $dateFrom = !empty($conditions['dateFrom'])
                ? new Carbon($conditions['dateFrom'] . " 00:00:00")
                : Carbon::createFromDate(0, 0 , 0);

            $dateTo = !empty($conditions['dateTo'])
                ? new Carbon($conditions['dateTo'] . " 23:59:59")
                : Carbon::now()->addDay();


            $builder->whereBetween('date', [
                $dateFrom,
                $dateTo
            ]);
        }


        if (array_key_exists('globalSearch', $conditions)) {

            $builder->orWhere('code', 'like', '%' . $conditions['globalSearch'] . '%')
                ->orWhere('id', 'like', '%' . $conditions['globalSearch'] . '%')
                ->orWhereHas('client', function (Builder $builder) use ($conditions) {
                    $builder->where('phone', 'like', '%' . $conditions['globalSearch'] . '%');
                })
                ->orWhereHas('client', function (Builder $builder) use ($conditions) {
                    $builder->where('name', 'like', '%' . $conditions['globalSearch'] . '%');
                });

        } else {

            if (array_key_exists('!orderStatus', $conditions)) {

                if (is_array($conditions['!orderStatus'])) {
                    $builder->whereNotIn('order_status', $conditions['!orderStatus']);
                } elseif (is_string($conditions['!orderStatus'])) {
                    $builder->where('order_status', '!=', $conditions['!orderStatus']);
                }
            }


            if (array_key_exists('orderStatus', $conditions)) {

                if (is_array($conditions['orderStatus'])) {
                    $builder->whereIn('order_status', $conditions['orderStatus']);
                } elseif (is_string($conditions['orderStatus'])) {
                    $builder->where('order_status', '=', $conditions['orderStatus']);
                }
            }

            if (array_key_exists('filialId', $conditions)) {

                if (is_array($conditions['filialId'])) {
                    $builder->whereIn('filial_id', $conditions['filialId']);
                } elseif (is_numeric($conditions['filialId'])) {
                    $builder->where('filial_id', '=', $conditions['filialId']);
                }
            }

            if (array_key_exists('clientId', $conditions)) {

                if (is_array($conditions['clientId'])) {
                    $builder->whereIn('client_id', $conditions['clientId']);
                } elseif (is_numeric($conditions['clientId'])) {
                    $builder->where('client_id', '=', $conditions['clientId']);
                }
            }

            if (array_key_exists('managerId', $conditions)) {

                if (is_array($conditions['managerId'])) {
                    $builder->whereIn('manager_id', $conditions['managerId']);
                } elseif (is_numeric($conditions['managerId'])) {
                    $builder->where('manager_id', '=', $conditions['managerId']);
                }
            }
            if (array_key_exists('courierId', $conditions)) {

                if (is_array($conditions['courierId'])) {
                    $builder->whereIn('courier_id', $conditions['courierId']);
                } elseif (is_numeric($conditions['courierId'])) {
                    $builder->where('courier_id', '=', $conditions['courierId']);
                }
            }

            if (array_key_exists('basketId', $conditions)) {
                if (is_array($conditions['basketId'])) {
                    $builder->whereIn('basket_id', $conditions['basketId']);
                } elseif (is_numeric($conditions['basketId'])) {
                    $builder->where('basket_id', '=', $conditions['basketId']);
                }
            }

            if (array_key_exists('!basketId', $conditions)) {

                if (is_array($conditions['!basketId'])) {
                    $builder->whereNotIn('basket_id', $conditions['!basketId']);
                } elseif (is_numeric($conditions['!basketId'])) {
                    $builder->where('basket_id', '!=', $conditions['!basketId']);
                }
            }

            if (array_key_exists('!basketSource', $conditions) && $conditions['!basketSource'] === 'aggregate') {
                $builder->whereHas('basket', function (Builder $query) {
                    $query->whereNotIn('basket_source', [ BasketSource::Yandex, BasketSource::DeliveryClub ]);
                });
            }

            if (array_key_exists('!deliveryType', $conditions) && $conditions['!deliveryType'] === 'aggregate') {
                $builder->whereHas('basket', function (Builder $query) {
                    $query->whereNotIn('delivery_type', [ DeliveryType::Yandex, DeliveryType::DeliveryClub ]);
                });
            }
            if (array_key_exists('deliveryType', $conditions) && is_string($conditions['deliveryType'])) {
                $builder->whereHas('basket', function (Builder $query) use ($conditions) {
                    $query->where('delivery_type', $conditions['deliveryType']);
                });
            }

            if (array_key_exists('date', $conditions) && is_string($conditions['date'])) {
                $builder->whereDate('date', '=', $conditions['date']);
            } elseif (array_key_exists('created', $conditions) && $conditions['created'] === 'today') {
                $builder->whereDate('date', '=', Carbon::now()->toDateString());
            }

            if (array_key_exists('completedAt', $conditions) && is_string($conditions['completedAt'])) {
                $builder->whereDate('completed_at', '=', $conditions['completedAt']);
            }

            if (array_key_exists('filterParam', $conditions)) {

                if ($conditions['filterParam'] === 'lateness') {
                    $builder->where('is_lateness', true);
                }

                if ($conditions['filterParam'] === 'aggregate') {

                    $builder->whereHas('basket', function (Builder $query) {
                        $query->whereIn('basket_source', [ BasketSource::DeliveryClub, BasketSource::Yandex ]);
                    });
                }
                if (OrderStatus::hasValue($conditions['filterParam'])) {
                    $builder->where('order_status', '=', $conditions['filterParam']);
                }

                if ($conditions['filterParam'] === 'inWork') {
                    $builder->whereIn('order_status', [ OrderStatus::Preparing,
                                                        OrderStatus::New,
                                                        OrderStatus::Assembly ]);
                }
            }

        }


        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'readyForIssue') {
            $builder->orderBy('order_status', 'DESC');
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'dateDesc') {
            $builder->orderBy('date', 'DESC');
        }

        if (array_key_exists('sort', $conditions) && $conditions['sort'] === 'new') {
            $builder->orderBy('id', 'DESC');
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function getCourierCell(): ?string
    {
        return $this->getModel()->courier_cell;
    }

    /**
     * @return string
     */
    public function code(): ?string
    {
        return $this->getModel()->code;
    }

    /**
     * @return string
     */
    public function getKitchenCell(): ?string
    {
        return $this->getModel()->kitchen_cell;
    }

    /**
     * @return string
     */
    public function getOrderStatus(): ?string
    {
        return $this->getModel()->order_status;
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {

    }


    /**
     * @return int
     */
    public function getClientAddressId(): int
    {
        return $this->getModel()->client_address_id;
    }


    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->getModel()->total_price;
    }

    /**
     * @return int
     */
    public function getBasketId(): int
    {
        return $this->getModel()->basket_id;
    }

    /**
     * @return int
     */
    public function getFilialId(): int
    {
        return $this->getModel()->filial_id;
    }

}
