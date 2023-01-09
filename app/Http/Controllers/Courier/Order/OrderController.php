<?php


namespace App\Http\Controllers\Courier\Order;


use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\OrderCourier\ChangePayment;
use App\Http\Requests\Courier\OrderCourier\ConfirmCancelling;
use App\Http\Resources\Courier\OrderHistoryResource;
use App\Http\Resources\Courier\OrderResource;
use App\Models\System\User;
use App\Services\Courier\CourierOrdersService;
use App\Services\CRM\System\UserService;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller
{

    protected string $resource = OrderResource::class;
    protected CourierOrdersService $ordersService;


    public function __construct(CourierOrdersService $ordersService)
    {
        $this->ordersService = $ordersService;
    }

    /**
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function index(): JsonResponse
    {

        $collection = $this->ordersService->getAllPendingOrders(auth()->user()->id);
        return $this->respondWithCollection($collection);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function myOrder(): JsonResponse
    {
        $collection = $this->ordersService->getOrdersInWork(auth()->user()->id);
        return $this->respondWithCollection($collection);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function history(): JsonResponse
    {
        $collection = $this->ordersService->getOrderHistory(auth()->user()->id);

        $this->resource = OrderHistoryResource::class;

        return $this->respondWithCollection($collection);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \App\Services\Courier\CourierServiceException
     */
    public function orderStart(int $id): JsonResponse
    {
        try {

            $this->ordersService->orderStart(auth()->user()->id, $id);

            $order = $this->ordersService->gerOrder(auth()->user()->id, $id);

        } catch (\Throwable $e) {
            return $this->responseError('Ошибка старта заказа:' . $e->getMessage(), 422, null, get_class($e));
        }

        return $this->respondWithItem($order);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function orderFinish(int $id): JsonResponse
    {
        try {

            $this->ordersService->orderFinish(auth()->user()->id, $id);

            $order = $this->ordersService->gerOrder(auth()->user()->id, $id);

        } catch (\Throwable $e) {
            return $this->responseError('Ошибка завершения заказа:' . $e->getMessage(), 422, null, get_class($e));
        }

        return $this->respondWithItem($order);
    }

    /**
     * @param int $id
     * @param ChangePayment $request
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\Courier\CourierServiceException
     */
    public function changePayment(int $id, ChangePayment $request): JsonResponse
    {
        $data = $request->all();

        $this->ordersService->changePayment(auth()->user()->id, $id, $data);

        $order = $this->ordersService->gerOrder(auth()->user()->id, $id);

        return $this->respondWithItem($order);
    }

    /**
     * @param ConfirmCancelling $request
     * @return JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function confirmCancelling(ConfirmCancelling $request): JsonResponse
    {

        $data = $request->all();

        $this->ordersService->confirmCancelling(auth()->user()->id, $data);

        return $this->responseSuccess(['success' => true]);
    }

}
