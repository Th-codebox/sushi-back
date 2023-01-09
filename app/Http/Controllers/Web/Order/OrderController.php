<?php


namespace App\Http\Controllers\Web\Order;


use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\Search;
use App\Http\Controllers\Traits\Sort;
use App\Http\Resources\Web\OrderResource;
use App\Libraries\Cache;
use App\Services\CRM\Order\OrderService;

class OrderController extends Controller
{
    use  Sort, Search;

    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;

        $this->resource = OrderResource::class;
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $sortParams = [];
        $searchParams = [];

        if ($this->hasSortRequest()) {
            $sortParams = $this->getSort();
        }

        if ($this->hasSearchRequest()) {
            $searchParams = $this->getSearchParams();
        }


        $page = request()->input('page', '1');

        $withoutRelation = request()->input('withoutRelation', false);


        $limit = request()->input('limit', 20);
        $limit = (is_numeric($limit) && (int)$limit >= 0 ? (int)$limit : 20);

        $offset = request()->input('offset', 0);
        $offset = (is_numeric($offset) && (int)$offset >= 0 ? (int)$offset : 0);

        $conditions = request()->all();

        $conditions['clientId'] = auth()->user()->id;
        $params = [
            'sort'            => $sortParams,
            'search'          => $searchParams,
            'conditions'      => $conditions,
            'page'            => $page,
            'withoutRelation' => $withoutRelation,
        ];

        return Cache::getInstance()->remember(get_class($this->service) . http_build_query($conditions), 1800, function () use ($params, $offset, $limit) {
            $result = $this->service::getAllWithPagination($params, $offset, $limit);

            $meta = [
                'total'   => $result->total(),
                'offset'  => $offset,
                'limit'   => $result->perPage(),
                'payload' => $params,
            ];

            return $this->respondWithCollection($result->items(), $meta);
        });


    }

    /**
     * @param int $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function getOrder(int $id)
    {
        $order = $this->service::findOne(['clientId' => auth()->user()->id, 'id' => $id])->getRepository()->getModel();

        return $this->respondWithItem($order);
    }

    /**
     * @throws \App\Repositories\RepositoryException
     */
    public function getActiveOrders()
    {
        $orders = $this->service->findListAsCollectionModel(['clientId' => auth()->user()->id, '!status' => [OrderStatus::Completed, OrderStatus::Canceled]]);

        return $this->respondWithCollection($orders);
    }

    /**
     * @throws \App\Repositories\RepositoryException
     */
    public function getOrderHistory()
    {
        $orders = $this->service->findListAsCollectionModel(['clientId' => auth()->user()->id, 'status' => [OrderStatus::Completed, OrderStatus::Canceled]]);

        return $this->respondWithCollection($orders);
    }

}
