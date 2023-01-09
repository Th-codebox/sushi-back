<?php


namespace App\Services\CRM\System;


use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Enums\TransactionOperationType;
use App\Enums\TransactionPaymentType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionTransitType;
use App\Http\Resources\CRM\UserResource;
use App\Libraries\Helpers\MoneyHelper;
use App\Models\System\FilialCashBox;
use App\Models\System\User;
use App\Repositories\System\FilialCashBoxRepository;
use App\Services\CRM\Courier\TransactionService;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Order\OrderService;
use Illuminate\Support\Carbon;


/**
 * Class UserService
 * @package App\Service\System
 *
 * @method FilialCashBoxRepository getRepository()
 */
class FilialCashBoxService extends CRMBaseService
{
    /**
     * FilialCashBoxService constructor.
     * @param FilialCashBoxRepository|null $repository
     * @throws CRMServiceException
     */
    public function __construct(?FilialCashBoxRepository $repository = null)
    {
        parent::__construct($repository);
    }

    /**
     * @param string|null $date
     * @return array
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function getShortInfo(?string $date) : array
    {
        $this->calcOrders($date);

        return [
            'totalProceed' => MoneyHelper::format($this->getRepository()->getModel()->totalProceed),
            'totalOrder'   => $this->getRepository()->getModel()->totalOrder,
        ];
    }

    /**
     * @param string|null $date
     * @return array
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function getAllInfo(?string $date): array
    {

        $this->calcOrders($date);
        $this->calcTransaction();
        $this->calcDebtor();

        return [
            'proceed'                 => [
                'proceedTotalToday'    => MoneyHelper::format($this->getRepository()->getModel()->proceedTotalToday),  // выручка сегодня
                'onlineProceedToday'   => MoneyHelper::format($this->getRepository()->getModel()->onlineProceedToday), // выручка онлайн
                'terminalProceedToday' => MoneyHelper::format($this->getRepository()->getModel()->terminalProceedToday), // выручка безнал
                'cashProceedToday'     => MoneyHelper::format($this->getRepository()->getModel()->cashProceedToday), // выручка нал
            ],
            'total'                   => [
                'terminalTotalToday' => MoneyHelper::format($this->getRepository()->getModel()->terminalTotalToday),  //  итого онлайн
                'cashTotalToday'     => MoneyHelper::format($this->getRepository()->getModel()->cashTotalToday),  //   итого безнал
                'checksTotalToday'   => $this->getRepository()->getModel()->checksTotalToday,  //   итог чеков
            ],
            'count'                   => [
                'cashCountToday'     => $this->getRepository()->getModel()->cashCountToday,   // колличество сделок нал
                'onlineCountToday'   => $this->getRepository()->getModel()->onlineCountToday,  //колличество сделок онлайн
                'terminalCountToday' => $this->getRepository()->getModel()->terminalCountToday,  //колличество сделок безнал (оно же чеки)
            ],
            'collection'              => [
                'collectionChecksSubtract'   => MoneyHelper::format($this->getRepository()->getModel()->collectionChecksSubtract),   //  инкасация - количество чеков отдано
                'collectionTerminalSubtract' => MoneyHelper::format($this->getRepository()->getModel()->collectionTerminalSubtract),  //  инкасация - количество безнала отдано
                'collectionCashSubtract'     => MoneyHelper::format($this->getRepository()->getModel()->collectionCashSubtract),  //  инкасация - количество нала отдано
                'collectionCashAdd'          => MoneyHelper::format($this->getRepository()->getModel()->collectionCashAdd),  //  инкасация - пополнение
            ],
            'begin'                   => [
                'beginCash'     => MoneyHelper::format($this->getRepository()->getModel()->begin_cash),   // остаток на начало смены  - нал
                'beginTerminal' => MoneyHelper::format($this->getRepository()->getModel()->begin_terminal),  // остаток на начало смены  - безнал
                'beginChecks'   => MoneyHelper::format($this->getRepository()->getModel()->begin_checks),  // остаток на начало смены  - чеков
            ],
            'debtorInfo'                   => [
                'cash'     => MoneyHelper::format($this->getRepository()->getModel()->debtorTotalCash),   // долг в кассу - нал
                'terminal' => MoneyHelper::format($this->getRepository()->getModel()->debtorTotalTerminal),  // долг в кассу - терминал
                'checks'   => $this->getRepository()->getModel()->debtorTotalChecks,  // долг в кассу - чеков
            ],
            'openAt'                  => $this->getRepository()->getModel()->open_at->toDateString(),    // открытие смены
            'debtorList'              => $this->getRepository()->getModel()->debtorList,  // список должников
            'correctTransactionsList' => $this->getRepository()->getModel()->correctTransactionsList,  // список транзакций
        ];
    }

    /**
     * @param string|null $date
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     */
    private function calcOrders(?string $date): void
    {
        $ordersForCalc = OrderService::findList(['filialId' => $this->getRepository()->getModel()->filial_id, 'completedAt' => $date ?: Carbon::now()->toDateString(), 'orderStatus' => OrderStatus::Completed]);

        foreach ($ordersForCalc as $orderForCalc) {


            /**
             * @var OrderService $orderForCalc
             */
            $this->getRepository()->getModel()->proceedTotalToday += $orderForCalc->getRepository()->getTotalPrice();

            if ($orderForCalc->getRepository()->getPaymentType() === PaymentType::Online) {
                $this->getRepository()->getModel()->onlineProceedToday += $orderForCalc->getRepository()->getTotalPrice();
                $this->getRepository()->getModel()->onlineCountToday++;
            }

            if ($orderForCalc->getRepository()->getPaymentType() === PaymentType::Terminal) {

                $this->getRepository()->getModel()->terminalProceedToday += $orderForCalc->getRepository()->getTotalPrice();
                $this->getRepository()->getModel()->terminalCountToday++;
            }

            if ($orderForCalc->getRepository()->getPaymentType() === PaymentType::Cash) {
                $this->getRepository()->getModel()->cashProceedToday += $orderForCalc->getRepository()->getTotalPrice();
                $this->getRepository()->getModel()->cashCountToday++;
            }

            $this->getRepository()->getModel()->totalProceed += $orderForCalc->getRepository()->getTotalPrice();

            $this->getRepository()->getModel()->totalOrder++;
        }


    }

    /**
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    private function calcDebtor()
    {
        $workSchedules = WorkScheduleService::findList(['filialId' => $this->getRepository()->getModel()->filial_id, 'role' => 'courier']);

        foreach ($workSchedules as $workSchedule) {

            /**
             * @var WorkScheduleService $workSchedule
             */
            $userModel = $workSchedule->getRepository()->getModel()->user;
            if($userModel) {

                $userModel->calcBalance();
                $userModel->scoperFullName();

                if ($userModel->terminalBalance < 0 || $userModel->cashBalance < 0) {
                    $this->getRepository()->getModel()->debtorTotalCash += $userModel->cashBalance;
                    $this->getRepository()->getModel()->debtorTotalChecks += $userModel->quantityChecks;
                    $this->getRepository()->getModel()->debtorTotalTerminal +=  $userModel->terminalBalance;

                    $this->getRepository()->getModel()->debtorList[] = [
                        'id'              => $userModel->id,
                        'name'            => $userModel->fullName,
                        'terminalBalance' => MoneyHelper::format($userModel->terminalBalance),
                        'checksCount'     => MoneyHelper::format($userModel->quantityChecks),
                        'cashBalance'     => MoneyHelper::format($userModel->cashBalance),
                        'totalBalance'    => MoneyHelper::format($userModel->terminalBalance + $userModel->cashBalance),
                    ];
                }
            }

        }

    }

    /**
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    private function calcTransaction(): void
    {
        $transactions = TransactionService::findList([
            'filialCashBoxId' => $this->getRepository()->getModel()->id,
            'status'          => TransactionStatus::Completed,
            'sort'            => 'new',
        ]);

        $this->getRepository()->getModel()->cashTotalToday = $this->getRepository()->getModel()->begin_cash;
        $this->getRepository()->getModel()->terminalTotalToday = $this->getRepository()->getModel()->begin_terminal;
        $this->getRepository()->getModel()->checksTotalToday = $this->getRepository()->getModel()->begin_checks;

        foreach ($transactions as $transaction) {

            /**
             * @var TransactionService $transaction
             */
            $transactionModel = $transaction->getRepository()->getModel();


            if ($transactionModel->status->is(TransactionStatus::Completed)) {

                if ($transactionModel->transit_type->is(TransactionTransitType::CollectionTransit)) {

                    if ($transactionModel->payment_type->is(TransactionPaymentType::Cash)) {

                        if ($transactionModel->operation_type->is(TransactionOperationType::Receive)) {
                            $this->getRepository()->getModel()->cashTotalToday += $transactionModel->price;
                            $this->getRepository()->getModel()->collectionCashAdd += $transactionModel->price;
                        } elseif ($transactionModel->operation_type->is(TransactionOperationType::Send)) {
                            $this->getRepository()->getModel()->cashTotalToday -= $transactionModel->price;
                            $this->getRepository()->getModel()->collectionCashSubtract += $transactionModel->price;
                        }

                    } else {
                        if ($transactionModel->operation_type->is(TransactionOperationType::Send)) {

                            $this->getRepository()->getModel()->terminalTotalToday -= $transactionModel->price;
                            $this->getRepository()->getModel()->checksTotalToday -= $transactionModel->quantity_checks;
                            $this->getRepository()->getModel()->collectionTerminalSubtract += $transactionModel->price;
                            $this->getRepository()->getModel()->collectionChecksSubtract += $transactionModel->quantity_checks;
                        }
                    }

                } else {

                    if ($transactionModel->payment_type->is(TransactionPaymentType::Cash)) {

                        if ($transactionModel->operation_type->is(TransactionOperationType::Receive)) {
                            $this->getRepository()->getModel()->cashTotalToday -= $transactionModel->price;
                        } elseif ($transactionModel->operation_type->is(TransactionOperationType::Send)) {
                            $this->getRepository()->getModel()->cashTotalToday += $transactionModel->price;
                        }

                    } else {


                        if ($transactionModel->operation_type->is(TransactionOperationType::Receive)) {
                            $this->getRepository()->getModel()->terminalTotalToday -= $transactionModel->price;
                            $this->getRepository()->getModel()->checksTotalToday -= $transactionModel->quantity_checks;
                        } elseif ($transactionModel->operation_type->is(TransactionOperationType::Send)) {

                            $this->getRepository()->getModel()->terminalTotalToday += $transactionModel->price;
                            $this->getRepository()->getModel()->checksTotalToday += $transactionModel->quantity_checks;
                        }
                    }
                }
            }

            $transactionModel->generateName();

            $sender = $transactionModel->sender ?? new User();
            $sender->scoperFullName();

            $this->getRepository()->getModel()->correctTransactionsList[] = [
                'id'            => (int)$transactionModel->id,
                'name'          => (string)$transactionModel->name,
                'price'         => MoneyHelper::format($transactionModel->price),
                'transitType'   => (string)$transactionModel->transit_type,
                'paymentType'   => (string)$transactionModel->payment_type,
                'operationType' => (string)$transactionModel->operation_type,
                'status'        => (string)$transactionModel->status,
                'time'          => (string)$transactionModel->updated_at->toTimeString(),
                'senderName'    => (string)$sender->fullName,
                'balanceAfter'  => MoneyHelper::format($transactionModel->balance_after),
                'cashBalance'   => MoneyHelper::format($transactionModel->balance_after),
            ];
        }
    }

    /**
     * @param $userId
     * @return bool
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function close($userId): bool
    {

        try {
            /**
             * @var WorkScheduleService $workScheduleService
             */
            $workScheduleService = WorkScheduleService::findOne(['date' => Carbon::now()->toDateString(), 'userId' => $userId]);
        } catch (\Throwable $e) {
            throw new CRMServiceException('Данный пользователь сегодня не работает');
        }

        $filialId = $workScheduleService->getRepository()->getModel()->workSpace->filial_id;

        try {

            $existOpenCashBoxes = self::findList(['filialId' => $filialId, 'closeAt' => null]);
        } catch (\Throwable $e) {
            throw new CRMServiceException('Открытых смен не найдено');
        }

        $lastExistData = [];

        foreach ($existOpenCashBoxes as $existOpenCashBox) {
            /**
             * @var FilialCashBoxService $existOpenCashBox
             */
            if ($existOpenCashBox !== null) {
                $existOpenCashBox->getAllInfo();

                $existOpenCashBox->edit([
                    'closeAt'     => Carbon::now(),
                    'endCash'     => $existOpenCashBox->getRepository()->getModel()->cashTotalToday,
                    'endTerminal' => $existOpenCashBox->getRepository()->getModel()->terminalTotalToday,
                    'endChecks'   => $existOpenCashBox->getRepository()->getModel()->checksTotalToday,
                ]);

                $lastExistData = [
                    'beginCash'     => $existOpenCashBox->getRepository()->getModel()->cashTotalToday,
                    'beginTerminal' => $existOpenCashBox->getRepository()->getModel()->terminalTotalToday,
                    'beginChecks'   => $existOpenCashBox->getRepository()->getModel()->checksTotalToday,
                ];
            }
        }

        $this::add(array_merge([
            'filialId' => $filialId,
            'openAt'   => Carbon::now(),
            'date'   => Carbon::now()->subDay(),
        ],$lastExistData));

        return true;
    }

}
