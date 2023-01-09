<?php


namespace App\Services\CRM\Order;


use App\Enums\BasketSource;
use App\Enums\CheckType;
use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\TaskInitiator;
use App\Events\Cooking\UpdateAssemblyCookingSchedule;
use App\Events\Cooking\UpdateColdCookingSchedule;
use App\Events\Cooking\UpdateHotCookingSchedule;
use App\Events\Order\ChangeOrderStatus;
use App\Events\Order\UpdateOrder;
use App\Events\Order\UpdateStatusesOrder;
use App\Jobs\Payment;
use App\Libraries\Helpers\SettingHelper;
use App\Libraries\OrderFilter;
use App\Libraries\Payment\Contracts\PaymentGateway;
use App\Libraries\Payment\UCS\UcsConfig;
use App\Libraries\Payment\UCS\UcsPaymentGateway;
use App\Libraries\System\FilialSettings;
use App\Models\Order\Basket;
use App\Models\Order\Order;
use App\Models\Store\PromoCode;
use App\Models\System\CookingSchedule;
use App\Notifications\PaymentLink;
use App\Repositories\Order\BasketRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\RepositoryException;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Store\ClientPromoCodeService;
use App\Services\CRM\Store\PromoCodeService;
use App\Services\CRM\System\CookingScheduleService;
use App\Services\CRM\System\PaymentService;
use App\Services\Payment\PaymentStatusService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;


/**
 * Class OrderService
 * @package App\Services\CRM\Store
 * @method OrderRepository getRepository()
 */
class OrderService extends CRMBaseService
{

    public function __construct(?OrderRepository $repository = null)
    {
        parent::__construct($repository);
    }

    /**
     * @param array $data
     * @return array
     * @throws CRMServiceException
     * @throws RepositoryException
     */
    protected function dataCorrection(array $data): array
    {
        try {
            $initiator = new TaskInitiator($data['initiator']);
        } catch (\Exception $e) {
            $initiator = TaskInitiator::fromValue(TaskInitiator::Default);
        }




        if ($this->getRepository()->getModel()->id) {

            $filialId = $this->getRepository()->getModel()->filial_id;

            /**
             * Kitchen cell set
             */
            /*if (array_key_exists('orderStatus', $data) && $data['orderStatus'] === OrderStatus::Preparing) {


                $kitchenCellCount = SettingHelper::getSettingValue('kitchenCellCount', $filialId);

                $allCells = range(1, $kitchenCellCount);

                $ordersBusyServices = $this::findList(['filialId' => $filialId, 'orderStatus' => ['confirm', 'preparing', 'assembly']]);

                foreach ($ordersBusyServices as $ordersBusyService) {
                    /**
                     * @var OrderService $ordersBusyService
                     *
                    unset($allCells[array_search((int)$ordersBusyService->getRepository()->getKitchenCell(), $allCells, true)]);
                }

                $data['kitchenCell'] = array_shift($allCells);
            }*/

            /**
             * Courier cell set
             */
            if (array_key_exists('orderStatus', $data) && $data['orderStatus'] === OrderStatus::Assembly) {

                $courierCellCount = SettingHelper::getSettingValue('CourierCellCount', $filialId);

                $allCells = range(1, $courierCellCount);

                $ordersBusyRepos = $this::findList(['filialId' => $filialId, 'orderStatus' => ['assembly', 'readyForIssue']]);

                foreach ($ordersBusyRepos as $ordersBusyRepo) {
                    /**
                     * @var OrderService $ordersBusyRepo
                     */

                    unset($allCells[array_search((int)$ordersBusyRepo->getRepository()->getCourierCell(), $allCells, true)]);
                }

                $data['courierCell'] = array_shift($allCells);
            }

            if (array_key_exists('orderStatus', $data)) {

                $orderStatus = (string)$this->getRepository()->getOrderStatus();

                $messageErrorChangeStatus = 'Невозможна непоследовательная смена статуса!';

                $orderModel = $this->getRepository()->getModel();

                if ( $data['orderStatus'] === OrderStatus::New
                    && $orderModel->payment_type->is(PaymentType::Online)
                    && !$orderModel->isPaidOnline()
                    && $initiator->isNot(TaskInitiator::BackgroundTask)
                ) {
                    throw new CRMServiceException('Невозможно выставить статус Новый у неоплаченного заказа с онлайн-оплатой!');
                }

                if ( $data['orderStatus'] === OrderStatus::Preparing
                    && $orderModel->payment_type->is(PaymentType::Online)
                    && !$orderModel->isPaidOnline() ) {
                    throw new CRMServiceException('Отправка в готовку не разрешена! Заказ не оплачен');
                }

                if ($data['orderStatus'] === OrderStatus::Confirm
                    && $orderModel->payment_type->is(PaymentType::Online)
                    && $orderModel->isPaidOnline()
                    && $orderModel->basket->total_price !== $orderModel->payment->total) {

                    throw new CRMServiceException("Сумма заказа превышает оплаченную сумму на сайте в размере {$orderModel->payment->getPaidSumInRub()}руб.!");

                }

                if ($data['orderStatus'] === OrderStatus::Canceled
                        && $orderModel->isPaidOnline()) {
                    $this->cancelPayment($orderModel);
                }







                if ($data['orderStatus'] === OrderStatus::Confirm && ($orderStatus !== OrderStatus::New && $orderStatus !== OrderStatus::WaitPayment)) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }




                if ($data['orderStatus'] === OrderStatus::Preparing && $orderStatus !== OrderStatus::Confirm) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }

                #TODO убрать костыль для заказов только сборки (например, только морс)
                if ($data['orderStatus'] === OrderStatus::Preparing && $orderStatus === OrderStatus::Confirm) {
                    $this->getRepository()->update($data);
                }

                if ($data['orderStatus'] === OrderStatus::Assembly && $orderStatus !== OrderStatus::Preparing) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }
                if ($data['orderStatus'] === OrderStatus::ReadyForIssue && $orderStatus !== OrderStatus::Assembly) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }
                if ($data['orderStatus'] === OrderStatus::InDelivery && $orderStatus !== OrderStatus::ReadyForIssue) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }
                if ($data['orderStatus'] === OrderStatus::Completed && $orderStatus !== OrderStatus::InDelivery) {
                    throw new CRMServiceException($messageErrorChangeStatus);
                }
                if ($data['orderStatus'] === OrderStatus::Canceled && $orderStatus === OrderStatus::Canceled) {
                    throw new CRMServiceException('Невозможно отменить завершенный заказ');
                }

                if ($data['orderStatus'] === OrderStatus::Confirm) {
                    $data['deadLine'] = Carbon::now()->addSeconds($this->getRepository()->getModel()->basket->timeToCompleteOrder())->unix();
                }

                if ($data['orderStatus'] === OrderStatus::Preparing) {
                    CookingScheduleService::add([
                        'orderId' => $this->getRepository()->getModel()->id,
                    ]);
                }
                if ($data['orderStatus'] === OrderStatus::Assembly) {

                    try {
                        $serviceCooking = CookingScheduleService::findOne([
                            'orderId'           => $this->getRepository()->getModel()->id,
                            'timeBeginAssembly' => null,
                        ]);

                        $serviceCooking->getRepository()->update([
                            'coldIsCompleted'   => true,
                            'hotIsCompleted'    => true,
                            'timeBeginAssembly' => Carbon::now(),
                        ]);

                        UpdateAssemblyCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                        UpdateHotCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                        UpdateColdCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                    } catch (\Throwable $e) {

                    }
                }

                if ($data['orderStatus'] === OrderStatus::ReadyForIssue) {

                    try {
                        $serviceCooking = CookingScheduleService::findOne([
                            'orderId'             => $this->getRepository()->getModel()->id,
                            'coldIsCompleted'     => true,
                            'hotIsCompleted'      => true,
                            'assemblyIsCompleted' => false,
                        ]);

                        $serviceCooking->getRepository()->update([
                            'assemblyIsCompleted'   => true
                        ]);

                        UpdateAssemblyCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                        UpdateHotCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                        UpdateColdCookingSchedule::dispatch($serviceCooking->getRepository()->getModel(), auth()->user());
                    } catch (\Throwable $e) {

                    }
                }

                if ($data['orderStatus'] === OrderStatus::Completed) {
                    $data['completedAt'] = Carbon::now()->toDateTimeString();
                    $data['isLateness'] = $this->getRepository()->getModel()->dead_line && (Carbon::now()->unix() - $this->getRepository()->getModel()->dead_line->unix()) > 0;
                }
            }

        } else {

            /**
             * @var Basket $basketSaveModel
             */
            $basketSaveModel = BasketService::findOne(['id' => $data['basketId'] ?? $this->getRepository()->getBasketId()])->getRepository()->getModel();

            $orderStatus = OrderStatus::New;

            /** Для нового заказа с онлайн-оплатой выставляем статус Ожидает оплаты */
            if ($basketSaveModel->payment_type->is(PaymentType::Online)) {
                $orderStatus = OrderStatus::WaitPayment;
            }


            $timeToCompleteOrder = $basketSaveModel->timeToCompleteOrder();


            $data['orderStatus'] = $orderStatus;
            $data['totalPrice'] = $basketSaveModel->total_price;
            $data['discountAmount'] = $basketSaveModel->discount_amount;
            $data['cookingAndDeliveryTime'] = $timeToCompleteOrder;
            $data['date'] = Carbon::now();

            try {
                /**
                 * @TODO FILIAL
                 */
                $lastCode = $this::findOne(['sort' => 'new'])->getRepository()->code();

                $lastCode = (int)$lastCode + 1;

                if ($lastCode > 9999) {
                    $data['code'] = 1000;
                } else {
                    $data['code'] = $lastCode;
                }
            } catch (\Throwable $e) {
                $data['code'] = 1000;
            }
        }

        return parent::dataCorrection($data); // TODO: Change the autogenerated stub
    }


    /**
     * @param int $basketId
     * @return array
     * @throws CRMServiceException
     * @throws RepositoryException
     */
    public static function validateBasketBeforeOrderProcessAndReturnData(int $basketId): array
    {

        try {
            /**
             * @var BasketRepository $basketRepo
             */
            $basketRepo = BasketService::findOne(['id' => $basketId])->getRepository();
        } catch (\Throwable $e) {
            throw new CRMServiceException('Корзина не найдена', 422);
        }

        $clientAddressId = $basketRepo->getModel()->client_address_id;
        $totalPrice = $basketRepo->getModel()->total_price;
        $discountAmount = $basketRepo->getModel()->discount_amount;
        $deliveryPrice = $basketRepo->getModel()->delivery_price;
        /**
         * @TODO FREE DELIVERY PRICE HARD CODE
         */
        $freeDelivery = 500;
        $clientMoney = $basketRepo->getModel()->client_money;
        $noCall = $basketRepo->getModel()->no_call;
        $deliveryType = (string)$basketRepo->getModel()->delivery_type;
        $paymentType = (string)$basketRepo->getModel()->payment_type;
        $filialId = $basketRepo->getModel()->filial_id;

        /*if ($noCall) {
            try {
                static::findOne(['clientId' => $basketRepo->getModel()->client_id, 'orderStatus' => OrderStatus::Completed]);

            } catch (\Throwable $e) {
                throw new CRMServiceException('Оформление без звонка не доступно для первого заказа');
            }
        }*/

        $totalPriceWithoutDiscount = $totalPrice + $discountAmount;

        if ($basketRepo->getModel()->clientPromoCode && (int)$basketRepo->getModel()->clientPromoCode->promoCode->sale_percent === 100) {

        } else {
            if ($freeDelivery && !in_array($deliveryType, [DeliveryType::Yandex, DeliveryType::DeliveryClub, DeliveryType::Self()]) && (($totalPrice / 100) < (int)SettingHelper::getSettingValue('totalForOrder', $filialId) || ($freeDelivery > $totalPrice))) {
                throw new CRMServiceException('Сумма заказа слишком мала. Минимальная сумма заказа ' . $freeDelivery / 100 . 'руб.', 422);
            }
        }


        $basketModel = $basketRepo->getModel();

        if ($basketModel->clientPromoCode) {
            $promoCodeModel = $basketModel->clientPromoCode->promoCode;
            $promoCodeModel->checkCanApplyToBasket($basketModel);
        }



        if (!$deliveryType) {
            throw new CRMServiceException('Выберите тип получения заказа', 422);
        }
        if (!$paymentType) {
            throw new CRMServiceException('Выберите тип оплаты', 422);
        }

        $totalPriceWithoutDiscount = $totalPrice + $discountAmount;

        if ($paymentType !== PaymentType::Online && (($totalPriceWithoutDiscount / 100) > SettingHelper::getSettingValue('totalForPreBay', $filialId))) {
            throw new CRMServiceException('Доступна только предоплата', 422);
        }

        if ($deliveryType === DeliveryType::Self && !$basketRepo->getModel()->filial_id) {
            throw new CRMServiceException('Для самовывоза должен быть выбран филиал', 422);
        }

        if ($deliveryType === DeliveryType::Delivery && !$basketRepo->getModel()->client_address_id) {
            throw new CRMServiceException('Для самовывоза должен быть выбран адресс клиента', 422);
        }

        return [
            'filialId'        => $basketRepo->getModel()->filial_id,
            'clientId'        => $basketRepo->getModel()->client_id,
            'clientAddressId' => $clientAddressId,
            'deliveryType'    => $deliveryType,
            'paymentType'     => $paymentType,
            'deliveryPrice'   => $deliveryPrice,
            'totalPrice'      => $totalPrice + $deliveryPrice,
            'clientMoney'     => $clientMoney,
            'discountAmount'  => $discountAmount,
        ];
    }

    /**
     * @return mixed|void
     */
    public function events()
    {
        ChangeOrderStatus::dispatch($this->getRepository()->getModel(), Auth()->user());
        UpdateOrder::dispatch($this->getRepository()->getModel(), Auth()->user());
        UpdateStatusesOrder::dispatch($this->getRepository()->getModel());
    }

    /**
     * @param array $data
     * @return \App\Services\CRM\CRMServiceInterface|OrderService|false|mixed
     * @throws CRMServiceException
     * @throws RepositoryException
     * @throws \ReflectionException
     */
    public static function add(array $data)
    {
        $data = array_merge($data, static::validateBasketBeforeOrderProcessAndReturnData($data['basketId']));


        $service = parent::add($data); // TODO: Change the autogenerated stub

        /**
         * @var Order $orderModel
         */
        $orderModel = $service->getRepository()->getModel();

        if ($service->getRepository()->getModel()->basket->clientPromoCode) {

            /**
             * @var PromoCode $promoCodeModel
             */
            $promoCodeModel = PromoCodeService::findOne(['id' => $service->getRepository()->getModel()->basket->clientPromoCode->promo_code_id])->getRepository()->getModel();

            if ($promoCodeModel->time_end && $promoCodeModel->time_end->unix() < Carbon::now()->unix()) {
                throw new CRMServiceException('Действие промокода закончено!');
            }

            if ($promoCodeModel->time_available_from && $promoCodeModel->time_available_to) {
                if (($promoCodeModel->time_available_from->hour > Carbon::now()->hour) || $promoCodeModel->time_available_to->hour <= Carbon::now()->hour) {
                    throw new CRMServiceException('Данный промокод доступен с ' . $promoCodeModel->time_available_from->format('H:i') . ' до ' . $promoCodeModel->time_available_to->format('H:i'));
                }
            }

            $promoCodeModel->checkCanApplyToBasket($orderModel->basket);
        }



        if ($orderModel->payment_type->is(PaymentType::Online)) {

            //$service = self::findOne(['id' => $service->getRepository()->getModel()->id]);
            //$service->edit(['orderStatus' => OrderStatus::WaitPayment]);

            /**
             * @var Order $orderModel
             */
            //$orderModel = $service->getRepository()->getModel();

            //$paymentGateway = new UcsPaymentGateway(new UcsConfig(new FilialSettings()));

            /** @var PaymentGateway $paymentGateway */
            $paymentGateway = App::make(PaymentGateway::class);

            $response = $paymentGateway->registerOrder(
                $orderModel,
                config('app.web_url') . "/thankyoupage/{$orderModel->id}",
                config('app.web_url') . "/thankyoupage/{$orderModel->id}"
            );


            $paymentModel = PaymentService::add([
                'paymentLink' => $response->getPaymentUrl(),
                'orderId'     => $orderModel->id,
                'total'       => $service->getRepository()->getModel()->total_price,
            ])->getRepository()->getModel();

            //Payment::dispatch($paymentModel->id);

        } elseif (
            $service->getRepository()->getModel()->basket->no_call
            || (string)$service->getRepository()->getModel()->basket->basket_source === BasketSource::Crm
            || (string)$service->getRepository()->getModel()->basket->delivery_type === DeliveryType::Yandex
            || (string)$service->getRepository()->getModel()->basket->delivery_type === DeliveryType::DeliveryClub
            || (string)$service->getRepository()->getModel()->basket->basket_source === BasketSource::Yandex
            || (string)$service->getRepository()->getModel()->basket->basket_source === BasketSource::DeliveryClub
        ) {

            /**
             * @var self $service
             */
            $service = self::findOne(['id' => $service->getRepository()->getModel()->id]);
            $service->edit(['orderStatus' => OrderStatus::Confirm]);

            /**
             * @TODO to_datetime calc
             */
            if (!$service->getRepository()->getModel()->basket->to_datetime) {

                $service = self::findOne(['id' => $service->getRepository()->getModel()->id]);

                if ($service->getRepository()->getOrderStatus() === OrderStatus::Confirm) {
                    $service->edit(['orderStatus' => OrderStatus::Preparing]);
                }

            }
        }

        if (array_key_exists('utmSource', $data) && is_string($data['utmSource'])) {
            $utms['utm_source'] = $data['utmSource'];
            activity()
                ->performedOn($service->getRepository()->getModel())
                ->causedBy($service->getRepository()->getModel()->client)
                ->withProperties(['utms' => $utms])
                ->log('order_utm');
        }

        return $service;
    }

    /**
     * @param array $data
     * @return bool
     * @throws CRMServiceException
     * @throws RepositoryException
     */
    public function edit(array $data): bool
    {
        $data = array_merge($data, static::validateBasketBeforeOrderProcessAndReturnData($this->getRepository()->getBasketId()));

        return parent::edit($data); // TODO: Change the autogenerated stub
    }

    /**
     * @param array $params
     * @return array
     * @throws CRMServiceException
     * @throws RepositoryException
     */
    public function getOrderStatusesWithCount(array $params = []): array
    {
        return (new OrderFilter($params))->getFilters();
    }

    /**
     * @return bool
     * @throws RepositoryException
     */
    public function changeOnNextStatus(): bool
    {

        $data['orderStatus'] = OrderStatus::nextStatus($this->getRepository()->getOrderStatus());
        $data['id'] = $this->getRepository()->getModel()->id;

        $result = parent::edit($data); // TODO: Change the autogenerated stub

        if ($data['orderStatus'] === OrderStatus::Confirm) {
            $service = self::findOne(['id' => $this->getRepository()->getModel()->id]);
            $data['orderStatus'] = OrderStatus::Preparing;
            $result = $service->edit($data);
        }

        return $result;
    }

    /**
     * @return bool
     * @throws RepositoryException
     */
    public function confirmStatus(): bool
    {

        $data['orderStatus'] = OrderStatus::Confirm;
        $data['id'] = $this->getRepository()->getModel()->id;

        return parent::edit($data); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     * @throws RepositoryException
     */
    public function cancelStatus(): bool
    {
        $order = $this->getRepository()->getModel();
        $data['orderStatus'] = OrderStatus::Canceled;
        $data['id'] = $order->id;

        $this->cancelPayment($order);


        $result = parent::edit($data); // TODO: Change the autogenerated stub
        if ($this->getRepository()->getModel()->basket->clientPromoCode) {
            ClientPromoCodeService::findOne(['id' => $this->getRepository()->getModel()->basket->clientPromoCode->id])->edit([
                "activated"   => false,
                "activatedAt" => null,
                "orderId"     => null,
            ]);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function sendLinkOnPayment(): array
    {
        $order = $this->getRepository()->getModel();

        if ($order->payment_type->is(PaymentType::Online)) {

            $order->client->notify(new PaymentLink($order->payment->payment_link));

            $order->client->phone = $order->basket->payment_phone ?? $order->client->phone;

            return [
                'send'    => true,
                'message' => 'Ссылка успешно отправлена',
            ];
        }

        return [
            'send'    => false,
            'message' => 'Способ оплаты не онлайн',
        ];

    }

    /**
     * @param string $type
     * @return array
     */
    public function groupBasketItemForCheck(string $type = CheckType::Main)
    {
        return $this->getRepository()->getModel()->basket->groupItems($type)->toArray();
    }

    public function cancelPayment(Order $order)
    {
        if ($order->isPaidOnline()) {
            /** @var PaymentStatusService $paymentStatusService */
            $paymentStatusService = App::make(PaymentStatusService::class);
            $paymentStatusService->cancelPayment($order);
        }

    }
}
