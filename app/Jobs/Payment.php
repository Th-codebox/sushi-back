<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Libraries\Payment\UCS\UcsConfig;
use App\Libraries\Payment\UCS\UcsPaymentGateway;
use App\Libraries\System\FilialSettings;
use App\Models\Order\Order;
use App\Models\System\Payment as PaymentModel;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class Payment
 * @package App\Jobs
 */
class Payment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $paymentId;


    /**
     * Payment constructor.
     * @param int $paymentId
     */
    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
        $this->queue = 'payment';
    }

    /**
     * @return bool
     * @throws \App\Libraries\Payment\Contracts\Exceptions\OrderStatusException
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     * @throws \ReflectionException
     */
    public function handle()
    {
        $paymentGateway = new UcsPaymentGateway(new UcsConfig(new FilialSettings()));
        /**
         * @var OrderService $orderService
         * @var PaymentService $paymentService
         */
        $paymentService = PaymentService::findOne(['id' => $this->paymentId]);

        if ((string)$paymentService->getRepository()->getModel()->payment_status === PaymentStatus::Success
            || (string)$paymentService->getRepository()->getModel()->payment_status === PaymentStatus::Cancel) {

            $orderService = OrderService::findOne(['id' => $paymentService->getRepository()->getModel()->order_id]);


            $status = $paymentGateway->getStatus($orderService->getRepository()->getModel());

            if ((string)$status->getStatus() === 'acknowledged') {
                $paymentService->edit(['paymentStatus' => PaymentStatus::Success]);
                $orderService->edit(['orderStatus' => OrderStatus::Preparing]);
            } elseif ((string)$status->getStatus() === 'not_authorized') {
                $paymentService->edit(['paymentStatus' => PaymentStatus::Cancel]);
                $orderService->edit(['orderStatus' => OrderStatus::Canceled]);
            } elseif ((string)$status->getStatus() === 'registered') {
                $this->delete();
                static::dispatch($this->paymentId);
            } else {
                $this->fail();
            }

            $paymentService->edit(['additionalInfo' => $status]);

        }
        return 0;
    }
}
