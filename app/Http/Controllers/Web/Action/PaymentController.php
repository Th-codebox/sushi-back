<?php


namespace App\Http\Controllers\Web\Action;


use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Libraries\Payment\UCS\UcsConfig;
use App\Libraries\Payment\UCS\UcsPaymentGateway;
use App\Libraries\System\FilialSettings;
use App\Models\Order\Order;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\PaymentService;
use App\Services\Payment\PaymentStatusService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class PaymentController extends Controller
{

    /**
     * Форсированная проверка статуса оплаты при запросе сенкью page на сайте
     * Если этот запрос по какой-то причине пройдет, то статус платежа проверится  по крону
     */
    public function thankPage($orderId, PaymentStatusService $paymentStatusService)
    {
        try {
            /** @var Order $order */
            $order = Order::findOrFail($orderId);
        } catch (ModelNotFoundException $e) {
            return $this->responseError("Ошибка. Заказ не найден");
        }


        if (!$order->payment) {
            return $this->responseError("У заказа нет онлайн-оплат");
        }

        try {
            $payment = $paymentStatusService->updateOrderPaymentStatus($order);
        } catch (\InvalidArgumentException $e) {
            return $this->responseError($e->getMessage());
        }


        if ($payment->isSuccess()) {
            return $this->responseSuccess(['paySuccess' => true, 'message' => 'Заказ оплачен']);
        } elseif ($payment->isCancel()) {
            return $this->responseSuccess(['paySuccess' => false, 'message' => 'Ошибка оплаты заказа']);
        } elseif ($payment->isWait()) {
            return $this->responseSuccess(['paySuccess' => false, 'message' => "Заказ зарегистрирован и ожидает оплаты"]);
        } else {
            return $this->responseError("Ошибка отображения заказа");
        }
    }
}
