<?php


namespace App\Libraries;


use App\Enums\BasketSource;
use App\Enums\OrderStatus;
use App\Models\Order\Order;
use App\Services\CRM\Order\OrderService;

class OrderFilter
{
    private array $orders;
    public array $statusesFilters = [];
    public array $latenessFilters = [];
    public array $aggregateFilters = [];
    public array $allFilters = [];
    public array $inWork = [];

    /**
     * OrderFilter constructor.
     * @param array|string[] $conditionsForOrders
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function __construct(array $conditionsForOrders = ['created' => 'today'])
    {
        $this->orders = OrderService::findList($conditionsForOrders);
    }


    /**
     * @return array
     */
    public function getFilters(): array
    {
        $this->inWork();
        $this->calcFiltersByStatuses();
        $this->calcLatenessFilters();
        $this->calcAggregate();
        $this->waitPayment();
        $this->calcAll();

        return array_merge($this->inWork, $this->statusesFilters, $this->aggregateFilters, $this->latenessFilters, $this->allFilters);
    }


    private function calcFiltersByStatuses(): void
    {

        $statuses = [
            OrderStatus::New,
            OrderStatus::Assembly,
            OrderStatus::ReadyForIssue,
            OrderStatus::Preparing,
            OrderStatus::InDelivery,
        ];

        foreach ($statuses as $status) {

            $statusCount = 0;

            foreach ($this->orders as $order) {

                /**
                 * @var OrderService $order
                 * **/

                $orderStatus = $order->getRepository()->getOrderStatus();

                if ((string)$status === (string)$orderStatus) {
                    $statusCount++;
                }

            }
            $this->statusesFilters[] = [
                'count'       => $statusCount,
                'ruName'      => __('order-filter.' .$status),
                'filterParam' => $status,
            ];
        }
    }

    private function calcLatenessFilters(): void
    {
        $latenessOrderCount = 0;

        foreach ($this->orders as $order) {

            /**
             * @var OrderService $order
             * **/


            if ( (string)$order->getRepository()->getModel()->order_status === OrderStatus::Completed && $order->getRepository()->getModel()->isLateness()) {
                $latenessOrderCount++;
            }
        }

        $this->latenessFilters[] = [
            'count'       => $latenessOrderCount,
            'ruName'      => __('order.lateness'),
            'filterParam' => 'lateness',
        ];
    }

    private function calcAggregate(): void
    {
        $aggregateOrderCount = 0;

        foreach ($this->orders as $order) {

            /**
             * @var OrderService $order
             * **/

            if ((string)$order->getRepository()->getModel()->basket->basket_source === BasketSource::DeliveryClub
                || (string)$order->getRepository()->getModel()->basket->basket_source === BasketSource::Yandex) {
                $aggregateOrderCount++;
            }
        }

        $this->aggregateFilters[] = [
            'count'       => $aggregateOrderCount,
            'ruName'      => __('order.aggregate'),
            'filterParam' => 'aggregate',
        ];
    }


    private function calcAll(): void
    {
        $count = 0;

        foreach ($this->orders as $order) {

            /**
             * @var OrderService $order
             * @var  Order $orderModel
             **/

            $orderModel = $order->getRepository()->getModel();

            if ($orderModel->order_status->isNot(OrderStatus::WaitPayment)) {
                $count++;
            }
        }

        $this->allFilters[] = [
            'count'       => $count,
            'ruName'      => __('order.all'),
            'filterParam' => 'all',
        ];
    }

    private function inWork(): void
    {
        $inWorkCount = 0;

        foreach ($this->orders as $order) {

            /**
             * @var OrderService $order
             * **/

            if (in_array((string)$order->getRepository()->getModel()->order_status, [OrderStatus::New, OrderStatus::Assembly, OrderStatus::Preparing], true)) {
                $inWorkCount++;
            }
        }
        $this->inWork[] = [
            'count'       => $inWorkCount,
            'ruName'      => __('order.inWork'),
            'filterParam' => 'inWork',
        ];
    }

    private function waitPayment(): void
    {
        $waitPaymentCount = 0;

        foreach ($this->orders as $order) {

            /**
             * @var OrderService $order
             * @var  Order $orderModel
             **/

            $orderModel = $order->getRepository()->getModel();

            if ($orderModel->order_status->is(OrderStatus::WaitPayment)) {
                $waitPaymentCount++;
            }
        }
        $this->statusesFilters[] = [
            'count'       => $waitPaymentCount,
            'ruName'      => __('order.waitPayment'),
            'filterParam' => OrderStatus::WaitPayment,
        ];
    }

}
