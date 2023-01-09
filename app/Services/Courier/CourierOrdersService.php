<?php


namespace App\Services\Courier;

use App\Enums\PaymentType;
use App\Enums\OrderStatus;
use App\Models\System\User;
use App\Repositories\Order\OrderRepository;
use App\Services\CRM\Order\BasketService;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CourierOrdersService
{

    private OrderRepository $orderRepository;
    private TransactionService $transactionService;

    public function __construct(orderRepository $orderRepository, TransactionService $transactionService)
    {
        $this->orderRepository = $orderRepository;
        $this->transactionService = $transactionService;

    }

    /**
     * @param int $id
     * @return Collection
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function getAllPendingOrders(int $id): Collection
    {
        /**
         * @var User $user
         */
        $user = UserService::findOne(['id' => $id])->getRepository()->getModel();

        $user->searchActualFilial();

        $orders = $this->orderRepository->findList([
            'filialId'     => $user->actualFilial->id  ?? null,
            'deliveryType' => 'delivery',
            'courierId'    => null,
            'orderStatus'  => [OrderStatus::Preparing, OrderStatus::Assembly, OrderStatus::ReadyForIssue],
            'sort'         => 'readyForIssue',
        ]);

        return $this->orderRepository->getModelCollections($orders);

    }

    /**
     * @param int $userId
     * @return Collection
     * @throws \App\Repositories\RepositoryException
     */
    public function getOrdersInWork(int $userId): Collection
    {
        $orders = $this->orderRepository->findList([
            'courierId'   => $userId,
            'orderStatus' => [OrderStatus::InDelivery],
        ]);

        return $this->orderRepository->getModelCollections($orders);
    }

    /**
     * @param int $userId
     * @return Collection
     * @throws \App\Repositories\RepositoryException
     */
    public function getOrderHistory(int $userId): Collection
    {
        $orders = $this->orderRepository->findList([
            'courierId'   => $userId,
            'orderStatus' => [OrderStatus::Completed, OrderStatus::Canceled],
            'sort'        => 'dateDesc',
        ]);

        return $this->orderRepository->getModelCollections($orders);
    }


    /**
     * @param int $userId
     * @param int $orderId
     * @throws CourierServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function orderStart(int $userId, int $orderId): void
    {

        try {
            $order = OrderService::findOne([
                'deliveryType' => 'delivery',
                'id'           => $orderId,
                'orderStatus'  => OrderStatus::ReadyForIssue,
            ]);
        } catch (\Throwable $e) {
            throw new CourierServiceException('Действие для этого заказа не доступно');
        }

        $order->edit(['orderStatus' => OrderStatus::InDelivery, 'courierId' => $userId, 'startAt' => Carbon::now()]);
    }

    /**
     * @param int $userId
     * @param int $orderId
     * @throws CourierServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function orderFinish(int $userId, int $orderId): void
    {
        try {
            /**
             * @var OrderService $order
             */
            $order = OrderService::findOne([
                'courierId'   => $userId,
                'id'          => $orderId,
                'orderStatus' => OrderStatus::InDelivery,
            ]);
        } catch (\Throwable $e) {
            throw new CourierServiceException('Действие для этого заказа не доступно');
        }


        try {

            $order->edit([
                'orderStatus' => OrderStatus::Completed,
                'completedAt' => Carbon::now(),
            ]);

            if ($order->getRepository()->getPaymentType() !== PaymentType::Online) {
                $this->transactionService->createTransactionByOrder($order->getRepository()->getModel());
            }

        } catch (\Throwable $e) {

            throw new CourierServiceException($e->getMessage());
        }

    }


    /**
     * @param int $userId
     * @param array $data
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function confirmCancelling(int $userId, array $data): void
    {
        $orders = $this->orderRepository->findList(['courierId' => $userId, 'id' => $data['orderIds']]);

        foreach ($orders as $order) {
            /**
             * @var orderRepository $order
             */
            $order->update(['orderStatus' => OrderStatus::Canceled, 'canceledConfirmByCourier' => true]);
        }

    }

    /**
     * @param int $userId
     * @param int $orderId
     * @param array $data
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function changePayment(int $userId, int $orderId, array $data): void
    {
        $order = $this->orderRepository->findOne(['courierId' => $userId, 'id' => $orderId, 'orderStatus' => OrderStatus::InDelivery]);

        BasketService::findOne(['id' => $order->getBasketId()])->edit(['paymentType' => $data['paymentType']]);

        $order->update(['paymentType' => $data['paymentType']]);
    }

    /**
     * @param int $userId
     * @param int $orderId
     * @return mixed
     * @throws CourierServiceException
     */
    public function gerOrder(int $userId, int $orderId)
    {
        try {
            $order = $this->orderRepository->findOne(['courierId' => $userId, 'id' => $orderId]);
        } catch (\Throwable $e) {
            throw new CourierServiceException('Действие для этого заказа не доступно');
        }

        return $order->getModel();
    }
}
