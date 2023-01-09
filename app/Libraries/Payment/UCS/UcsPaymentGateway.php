<?php
/**
 * Docs https://tws.egopay.ru/docs/v2/
 *
 * Тестовые данные:
    MasterCard: 5100 0000 0000 0008 ExpDate: 12/2025 CVV2: 222 Cardholder: Pupkin Vasya 3-DS: 123
Visa: 4111 1111 1111 1111 ExpDate: 12/2025 CVV2: 222 CardHolder: Pupkin Vasya.
По первой будет успешная оплата, а по второй отказ.
 *
 */

namespace App\Libraries\Payment\UCS;


use Amount;
use App\Libraries\Payment\Contracts\Exceptions\CancelOrderFailed;
use App\Libraries\Payment\Contracts\Exceptions\OrderStatusException;
use App\Libraries\Payment\Contracts\Exceptions\RefundOrderFailed;
use App\Libraries\Payment\Contracts\Exceptions\RegisterOrderFailed;
use App\Libraries\Payment\Contracts\Exceptions\RejectOrderFailed;
use App\Libraries\Payment\Contracts\OrderStatusResponse;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Libraries\Payment\Contracts\OrderRegisterResponse;
use App\Libraries\Payment\UCS\Responses\SuccessOrderRegister;
use App\Libraries\Payment\UCS\Responses\UcsPaymentStatus;
use App\Libraries\Payment\UCS\Services\Status\GetByOrderRequest;
use App\Libraries\Payment\UCS\Services\Status\StatusSoapClient;
use App\Models\Order\Order;
use CustomerInfo;
use get_status;
use Illuminate\Support\Facades\Log;
use OrderID;
use OrderInfo;
use OrderItem;
use PostEntry;
use register_online;
use SoapFault;
use SoapVar;

class UcsPaymentGateway implements PaymentGateway
{
    protected $orderSoapClient;
    protected StatusSoapClient $statusSoapClient;
    protected UcsConfig $config;


    public function __construct(UcsConfig $config)
    {
        require_once 'lib/orderv2.php'; #TODO PSR4

        $this->config = $config;

        $this->orderSoapClient = new \orderv2(null, $this->config->getOrderClientOptions());
        $this->statusSoapClient = new StatusSoapClient(null, $this->config->getStatusClientOptions());
    }


    /**
     * Регистрация платежа в платежной системе
     *
     * @param Order $shopOrder
     * @param string $returnUrlOk перенаправлять в случае удачной оплаты
     * @param string $returnUrlFail перенаправлять в случае ошибки
     *
     * @return OrderRegisterResponse
     *
     * @throws RegisterOrderFailed
     */
    public function registerOrder(
        Order $shopOrder,
        string $returnUrlOk,
        string $returnUrlFail
    ): OrderRegisterResponse
    {
        // Setup the RemoteFunction parameters
        $request = new register_online();
        $order = new OrderID();
        $order->shop_id = $this->config->getShopId($shopOrder->filial_id);
        $order->number = $shopOrder->id;

        $cost = new Amount();
        $cost->amount = $shopOrder->getTotalPriceInRub();
        $cost->currency = "RUB";

        $customer = new CustomerInfo();
        $description = new OrderInfo();
        $description->paytype = "card";

        //$settings['basket_items'] - массив элементов товаров
        //Каждый элемент содержит:
        //Price - цена
        //VAT - процент налога
        //VAT_VALUE - сумма налога
        //QUANTITY - количество
        //NAME - название

 /*       $settings['basket_items'][] = array(
            'PRICE' => 850,
            'NAME' => 'crusher red',
            'VAT' => 0,
            'VAT_VALUE' => 0,
            'QUANTITY' => 2
        );


        $arBasketItems = $settings['basket_items'];
        $arItems = array();
        $countBasketItem = count($arBasketItems);
        for ($i = 0; $i < $countBasketItem; $i++) {
            $itemCost = new Amount();
            $itemCost->amount = $arBasketItems[$i]['PRICE'];
            $itemCost->currency = 'RUB';

            //Единственный способ в Soap добавить атрибут type='vat', который был найден на момент создания.
            //TODO: Найти другой способ
            //Налог должен быть int
            $itemTaxes = array(
                new SoapVar("<tax type='vat'><percentage>" . substr($arBasketItems[$i]['VAT'], 0, 2) . "</percentage><amount><currency>RUB</currency><amount>" . $arBasketItems[$i]['VAT_VALUE'] . "</amount></amount></tax>", XSD_ANYXML),
            );


            $item = new OrderItem();
            $item->number = "1234";
            $item->amount = $itemCost;
            $item->typename = 'goods';
            $item->quanity = $arBasketItems[$i]['QUANTITY'];
            $item->name = $arBasketItems[$i]['NAME'];
            $item->taxes = new SoapVar($itemTaxes, SOAP_ENC_OBJECT);

            $arItems[] = new SoapVar($item, SOAP_ENC_OBJECT, null, null, 'OrderItem');
        }


        $description->sales = new SoapVar(
            $arItems,
            SOAP_ENC_OBJECT
        );*/



        $language = new PostEntry();
        $language->name = "Language";
        $language->value = "ru";

        $cardtype = new PostEntry();
        $cardtype->name = "ChoosenCardType";
        $cardtype->value = "VI";

        $returnUrlOkEntry = new PostEntry();
        $returnUrlOkEntry->name = 'ReturnURLOk';
        $returnUrlOkEntry->value = $returnUrlOk;

        $returnUrlFaultEntry = new PostEntry();
        $returnUrlFaultEntry->name = 'ReturnURLFault';
        $returnUrlFaultEntry->value = $returnUrlFail;

        $request->order = $order;
        $request->cost = $cost;
        $request->customer = $customer;
        $request->description = $description;

        $postdata = new SoapVar([
            new SoapVar($language, SOAP_ENC_OBJECT, null, null, "PostEntry"),
            new SoapVar($cardtype, SOAP_ENC_OBJECT, null, null, "PostEntry"),
            new SoapVar($returnUrlOkEntry, SOAP_ENC_OBJECT, null, null, 'PostEntry'),
            new SoapVar($returnUrlFaultEntry, SOAP_ENC_OBJECT, null, null, 'PostEntry')
        ], SOAP_ENC_OBJECT);
        //$postdata = new SoapVar(array($language, $cardtype), SOAP_ENC_OBJECT);

        $request->postdata = $postdata;


        // Call RemoteFunction ()
        try {
            $info = $this->orderSoapClient->register_online($request);
        } catch (SoapFault $fault) {
            throw new RegisterOrderFailed($this->getErrorMessage($fault->faultstring));
        }

        return new SuccessOrderRegister($info);
    }


    /**
     * Проверка статуса платежа
     *
     * @param Order $shopOrder
     * @return OrderStatusResponse
     *
     * @throws OrderStatusException
     */
    public function getStatusOld(Order $shopOrder): OrderStatusResponse
    {

        $request = new get_status();
        $order = new OrderID();
        $order->shop_id = $this->config->getShopId($shopOrder->filial_id);
        $order->number = $shopOrder->id;
        $request->order = $order;

        Log::channel('payment')->debug('Order status', [
            "order" => $order,
            'order_id' => $shopOrder->id
        ]);

        try {
            $info = $this->orderSoapClient->get_status($request);

            Log::channel('payment')->debug('Order status response', [
                'order_id' => $shopOrder->id,
                'info' => $info
            ]);

        } catch (SoapFault $fault) {
            throw new OrderStatusException($this->getErrorMessage($fault->faultstring));
        }

        return new UcsPaymentStatus($info);
    }

    /**
     * Проверка статуса платежа
     *
     * @param Order $shopOrder
     * @return UcsPaymentStatus
     *
     * @throws OrderStatusException
     */
    public function getStatus(Order $shopOrder): UcsPaymentStatus
    {

        $request = new GetByOrderRequest();

        $order = new Entities\OrderId();
        $order->shop_id = $this->config->getShopId($shopOrder->filial_id);
        $order->number = $shopOrder->id;

        $request->order = $order;

        Log::channel('payment')->debug('Order status', [
            "order" => $order,
            'order_id' => $shopOrder->id
        ]);

        try {
            $info = $this->statusSoapClient->getByOrder($request);

            Log::channel('payment')->debug('Order status response', [
                'order_id' => $shopOrder->id,
                'info' => $info
            ]);

        } catch (SoapFault $fault) {
            throw new OrderStatusException($this->getErrorMessage($fault->getMessage()));
        }

        return new UcsPaymentStatus($info);
    }


    public function confirmOrder(Order $shopOrder)
    {
        // TODO: Implement confirmOrder() method.
    }


    public function cancelOrder(Order $shopOrder)
    {
        $status = $this->getStatus($shopOrder);

        if (!$status->isCompleted()) {
            throw new CancelOrderFailed("статус заказа в шлюзе {$status->value} ({$status->description})", $shopOrder);
        }

        if (empty($status->payment)) {
            throw new CancelOrderFailed("в ответе шлюза тсутствует информация о платеже", $shopOrder);
        }

        $paymentId = $status->payment->id;


        if ($status->payment->status == 'authorized') {
            return $this->reject($shopOrder, $paymentId);
        } else {
            // Если деньги уже списаны не даем отменить платеж
            throw new CancelOrderFailed("деньги уже списаны (статус платежа {$status->payment->status})", $shopOrder);
            //$this->refund($shopOrder, $paymentId);
        }
    }

    private function reject(Order $shopOrder, $paymentId)
    {
        $request = new \reject();

        $order = new OrderID();
        $order->shop_id = $this->config->getShopId($shopOrder->filial_id);
        $order->number = $shopOrder->id;
        $request->order = $order;
        $request->payment_id = $paymentId;


        Log::channel('payment')->debug('Order reject', [
            "order" => $order,
            'order_id' => $shopOrder->id
        ]);

        try {
            $info = $this->orderSoapClient->reject($request);

            Log::channel('payment')->debug('Order reject response', [
                'order_id' => $shopOrder->id,
                'info' => $info
            ]);

            return $info;

        } catch (SoapFault $fault) {
            dd($this->orderSoapClient->__getLastRequest());
            //throw new RejectOrderFailed($fault->getMessage(), $fault->faultcode);
        }

    }


    private function refund(Order $shopOrder, $paymentId)
    {
        $request = new \refund();

        $order = new OrderID();
        $order->shop_id = $this->config->getShopId($shopOrder->filial_id);
        $order->number = $shopOrder->id;
        $request->order = $order;

        $cost = new Amount();
        $cost->amount = $shopOrder->payment->getPaidSumInRub();
        $cost->currency = "RUB";
        $request->cost = $cost;

        $request->refund_id = $shopOrder->payment->id;
        $request->payment_id = $paymentId;

        //dd($request);

        Log::channel('payment')->debug('Order refund', [
            "order" => $order,
            'order_id' => $shopOrder->id
        ]);

        try {
            $info = $this->orderSoapClient->refund($request);

            Log::channel('payment')->debug('Order refund response', [
                'order_id' => $shopOrder->id,
                'info' => $info
            ]);

            return $info;

        } catch (SoapFault $fault) {
            throw new RefundOrderFailed($fault->getMessage(), $fault->faultcode);
        }


    }


    private function getErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case "ALREADY_PROCESSED" :
                return "операция по данному заказу уже выполнена, повтор невозможен";
            case "ACCESS_DENIED" :
                return "запрос к ПШ отклонен / доступ запрещён";
            case "SYSTEM_ERROR" :
                return "сбой в работе ПШ или некорректно составленный запрос";
            case "FATAL_ERROR" :
                return "критическая ошибка";
            case "ORDER_ERROR" :
                return "неверные параметры заказа";
            case "INVALID_ORDER" :
                return "неизвестный для системы заказ";
            case "PRICING_ERROR" :
                return "ошибка при проверке стоимости заказа";
            case "BOOKING_ERROR" :
                return "ошибка при проверке статуса заказа";
            case "WRONG_AMOUNT" :
                return "переданная стоимость заказа неверна";


            /* | Проверьте итоговую стоимость заказа и стоимость в запросе. Повторите отправку запроса | | SESSION_ERROR | ошибка при установке сессии | Повторите отправку запроса | | MAX_ATTEMPTS | превышено возможное количество попыток оплаты | В системе существует антифрод настройка, ограничивающая количество возможных попыток оплатить заказ. Если такая ошибка получена – стоит обратить внимание на Клиента | | REPEAT_NEEDED | запрос не может быть обработан в данный момент | Повторите отправку запроса | | UNKNOWN_TAX_SOURCE | в значении параметра source допущена ошибка или запрос содержит код провайдера фискальных данных, который не был настроен | Перепроверьте запрос. Если ошибки нет свяжитесь со службой тех.поддержки| | INVALID_TAX_DATA | допущена ошибка в переданном блоке данных | Проверьте правильность отправленного запроса. Исправьте ошибку, повторите попытку обращения | | AMBIGUOUS_TAXATION_SYSTEM | значение параметра sno отсутвует в запросе | Проверьте правильность отправленного запроса. Если ошибки нет свяжитесь со службой тех.поддержки | | UNKNOWN_TAXATION_SYSTEM | переданный в запросе код sno отсутвует в настройках ИМ | Проверьте правильность отправленного запроса. Если ошибки нет свяжитесь со службой тех.поддержки | | NO_TAXATION_SYSTEM | отсутствуют настройки фискализации продаж | Свяжитесь со службой тех.поддержки |*/
        }
    }
}
