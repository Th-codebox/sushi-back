<?php


namespace App\Services\Payment;


use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\TaskInitiator;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Models\Order\Order;
use App\Models\System\Payment;
use App\Models\System\Transaction;
use App\Services\CRM\Order\OrderService;
use Illuminate\Support\Facades\DB;

class PaymentStatusService
{
    private PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }


    /**
     * Проверяем статус платежа у заказов ожидающих оплату
     *
     * @param Order $order
     * @return \App\Models\System\Payment
     * @throws \App\Libraries\Payment\Contracts\Exceptions\OrderStatusException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function updateOrderPaymentStatus(Order $order)
    {
        //if ($order->order_status->isNot(OrderStatus::WaitPayment())) {
            //throw new \InvalidArgumentException("Проверить статус оплаты можно только у заказов в статусе 'Ожидает оплату'");
        //}

        if (!$order->payment) {
            throw new \InvalidArgumentException("У заказа ID {$order->id} нет зарегестрированных оплат");
        }

        if (!$order->payment->isWait()) {
            /** Проверка на соответствие статуса заказа и статуса оплаты */
            $this->syncOrderPaymentStatus($order);

            return $order->payment;
        }


        $status = $this->gateway->getStatus($order);


        if ($status->isCompleted()) {

            DB::transaction(function () use ($order) {
                $order->payment->complete();

                #TODO отрефакторить переход по статусам
                $orderService = OrderService::findOne([ 'id' => $order->id ]);
                $orderService->edit([
                    'orderStatus' => OrderStatus::New,
                    'initiator' => TaskInitiator::BackgroundTask
                ]);
            });

        } elseif ($status->isCancelled()) {

            DB::transaction(function () use ($order) {
                $order->payment->cancel();
                $order->order_status = OrderStatus::Canceled;
                $order->save();

                #TODO отрефакторить переход по статусам и убрать отмену заказа
                //$orderService = OrderService::findOne([ 'id' => $order->id ]);
                //$orderService->edit([ 'orderStatus' => OrderStatus::Canceled() ]);
            });

        }

        return $order->payment;
    }

    public function updateAllWaitingPaymentOrders()
    {

        try {
            /*$orders = Order::with('payment')
                ->where('order_status', OrderStatus::WaitPayment)
                ->whereHas('payment')
                ->get();*/

            $payments = Payment::with('order')
                ->where('payment_status', PaymentStatus::Wait)
                ->get();


            $payments->each(fn(Payment $payment) => $this->updateOrderPaymentStatus($payment->order));
        } catch (\Exception $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        }

    }

    public function cancelPayment(Order $order)
    {
        $this->gateway->cancelOrder($order);
        $order->payment->refund();
    }


    private function syncOrderPaymentStatus(Order $order)
    {
        if ($order->payment->isSuccess() && $order->order_status->is(OrderStatus::WaitPayment)) {
            $orderService = OrderService::findOne(['id' => $order->id]);
            $orderService->edit(['orderStatus' => OrderStatus::New]);
        }

        if ($order->payment->isCancel() && $order->order_status->is(OrderStatus::WaitPayment)) {
            $orderService = OrderService::findOne(['id' => $order->id]);
            $orderService->edit(['orderStatus' => OrderStatus::Canceled]);
        }
    }


}
