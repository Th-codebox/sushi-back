<?php

namespace App\Http\Controllers\CRM\Order;

use App\Enums\BasketStatus;
use App\Enums\CheckType;
use App\Enums\OrderStatus;
use App\Exceptions\Http\Controller\RequestClassNotFoundException;
use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\Order\CreateOrder;
use App\Http\Requests\CRM\Order\FilterMenuItemsByManufacturerType;
use App\Http\Requests\CRM\Order\UpdateOrder;
use App\Http\Resources\CRM\Order\SingleMenuItemResource;
use App\Http\Resources\CRM\OrderResource;
use App\Models\Order\Order;
use App\Services\CRM\Order\BasketService;
use App\Services\CRM\Order\OrderService;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends BaseCRMController
{
    public function __construct(OrderService $service)
    {

        parent::__construct($service, CreateOrder::class, UpdateOrder::class, OrderResource::class);
    }

    protected function conditions(): array
    {
        $conditions = parent::conditions(); // TODO: Change the autogenerated stub

        if (!request()->get('orderField')) {
            $conditions['sort'] = 'old';
        }

        if ($dateFrom = request()->get('dateFrom')) {
            $conditions['dateFrom'] = $dateFrom;
        }

        if ($dateTo = request()->get('dateTo')) {
            $conditions['dateTo'] = $dateTo;
        }


        if ($globalSearch = request()->get('globalSearch')) {
            $conditions['globalSearch'] = $globalSearch;
        } else {
            if ($filialId = request()->get('filialId')) {
                $conditions['filialId'] = $filialId;
            }
            if ($orderStatus = request()->get('orderStatus')) {
                $conditions['orderStatus'] = $orderStatus;
            }
            if ($filterParam = request()->get('filterParam')) {
                $conditions['filterParam'] = $filterParam;
            }
            if ($date = request()->get('date')) {
                $conditions['date'] = $date;
            }
        }

        return $conditions;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
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

        $payLoad = $sortParams + $searchParams;

        $page = request()->input('page', '1');

        if (is_string($page) && $page !== '' && (int)$page > 0) {
            $payLoad['page'] = $page;
        }

        $limit = request()->input('limit', 20);
        $limit = (is_numeric($limit) && (int)$limit >= 0 ? (int)$limit : 20);

        $offset = request()->input('offset', 0);
        $offset = (is_numeric($offset) && (int)$offset >= 0 ? (int)$offset : 0);

        $conditions = static::conditions();
        $filterParam = request()->get('filterParam');
        if ($filterParam === 'all') {


            $conditions['sort'] = false;
            $sortParams = [
                'sortField' => 'id',
                'sortMethod' => 'DESC',
            ];

            $conditions['!orderStatus'] = OrderStatus::WaitPayment;

        }
        $result = $this->service::getAllWithPagination(
            [
                'sort'       => $sortParams,
                'search'     => ((count($searchParams) === 2)
                    ? [
                        Str::snake($searchParams['search']) => [
                            'param'    => $searchParams['text'],
                            'splitBy'  => null,
                            'wildCard' => '*',
                        ],
                    ]
                    : []
                ),
                'conditions' => $conditions,
                'page'       => $page,
            ],
            $offset,
            $limit
        );

        $meta = [
            'total'   => $result->total(),
            'offset'  => $offset,
            'limit'   => $result->perPage(),
            'payload' => $payLoad,
        ];

        return $this->respondWithCollection($result->items(), $meta);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function show($id)
    {
        return parent::show($id); // TODO: Change the autogenerated stub
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws RequestClassNotFoundException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function store(Request $request)
    {

        try {
            $request = $this->requestCreateClass::createFrom($request);
        } catch (\Throwable $e) {
            throw new RequestClassNotFoundException('???????????? ???????????? ??????????????????');
        }

        $params = $request->all();


        unset($params['id']);

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails() || !$params) {
            return $this->responseError('???????????????? ??????????????????', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }

        $params['date'] = Carbon::now()->toDateString();
        $params['crm'] = true;

        BasketService::findOne(['id' => $params['basketId']])->edit(['status' => BasketStatus::inOrder]);
        /**
         * @var OrderService $service
         */
        $service = $this->service::add($params);


        return $this->refreshAndReturnOrder($service->getRepository()->getModel()->id);


    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws RequestClassNotFoundException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function update(Request $request, $id)
    {
        $service = $this->service::findOne(['id' => $id]);

        $request->request->add([
            'id' => $id,
        ]);

        try {
            $request = $this->requestUpdateClass::createFrom($request);
        } catch (\Throwable $e) {
            throw new RequestClassNotFoundException('???????????? ???????????? ??????????????????');
        }

        $params = $request->all();

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails()) {
            return $this->responseError('???????????????? ??????????????????', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }

        $service->edit($request->all());

        return $this->refreshAndReturnOrder($id);
    }

    /**
     * @return JsonResponse
     */
    public function getStatuses()
    {
        $params = [
            'filialId' => request()->get('filialId'),
            'filialAccess' => request()->get('filialAccess'),
            'created'      => 'today',
            'date'         => request()->get('date'),
        ];


        return $this->responseSuccess(['data' => $this->service->getOrderStatusesWithCount($params)]);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function changeOnNextStatus($id)
    {
        $service = $this->service::findOne(['id' => $id]);


        $service->changeOnNextStatus();


        return $this->refreshAndReturnOrder($id);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function cancelOrder($id)
    {
        $service = $this->service::findOne(['id' => $id]);


        $service->cancelStatus();


        return $this->refreshAndReturnOrder($id);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function confirmOrder($id)
    {

        $service = $this->service::findOne(['id' => $id]);
        $service->confirmStatus();

        $service = $this->service::findOne(['id' => $id]);
        $service->changeOnNextStatus();


        return $this->refreshAndReturnOrder($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function refreshAndReturnOrder($id): JsonResponse
    {
        try {
            $orderModel = $this->service::findOne(['id' => $id])->getRepository()->getModel();
        } catch (\Exception $e) {
            return $this->responseError('???????????? ???????????????????? ?? ?????????????????? ????????????: ' . $e->getMessage());
        }

        return $this->respondWithItem($orderModel);
    }


    /**
     * @param int $orderId
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function sendLinkOnPayment(int $orderId)
    {
        $service = $this->service::findOne(['id' => $orderId]);

        return $this->responseSuccess($service->sendLinkOnPayment());
    }

    /**
     * @param int $orderId
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getMainCheck(int $orderId)
    {
        $service = $this->service::findOne(['id' => $orderId]);

        return $this->responseSuccess(['date' => $service->groupBasketItemForCheck()]);
    }

    /**
     * @param int $orderId
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getColdCheck(int $orderId)
    {
        $service = $this->service::findOne(['id' => $orderId]);

        return $this->responseSuccess(['date' => $service->groupBasketItemForCheck(CheckType::Cold)]);
    }

    /**
     * @param int $orderId
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getHotCheck(int $orderId)
    {

        $service = $this->service::findOne(['id' => $orderId]);

        return $this->responseSuccess(['date' => $service->groupBasketItemForCheck(CheckType::Hot)]);
    }

    public function getSingleMenuItems(int $orderId, FilterMenuItemsByManufacturerType $request)
    {
        /** @var Order $order */
        $order = Order::with(
            'basket',
            'basket.items.menuItem.technicalCard'
        )->findOrFail($orderId);

        //dump($order->basket->items->toArray());

        $result = $order
            ->getAllSingleMenuItems()
            ->filterByManufacturerType($request->getManufacturerType());

        //dd(SingleMenuItemResource::collection($result)->toArray($request));

        return $this->responseSuccess(['items' => SingleMenuItemResource::collection($result)]);
    }
}
