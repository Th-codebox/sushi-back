<?php


namespace App\Services\Courier;


use App\Enums\PaymentType;
use App\Enums\TransactionOperationType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionTransitType;
use App\Events\User\CreateTransaction;
use App\Models\System\Transaction;
use App\Models\Order\Order;
use App\Models\System\User;
use App\Models\System\WorkSchedule;
use App\Repositories\Courier\TransactionRepository;
use App\Repositories\RepositoryException;
use App\Repositories\System\UserRepository;
use App\Services\CRM\System\FilialCashBoxService;
use App\Services\CRM\System\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


class TransactionService
{

    private TransactionRepository $transactionRepository;
    private UserRepository $userRepository;

    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Order $order
     * @return Transaction
     * @throws CourierServiceException
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function createTransactionByOrder(Order $order): Transaction
    {

        if ($order->client_id) {


            try {
                $filialCashBoxId = FilialCashBoxService::findOne(['filialId' => $order->filial_id, 'day' => 'today','sort'=> 'new'])->getRepository()->getModel()->id;
            } catch (\Throwable $e) {
                $filialCashBoxId = FilialCashBoxService::findOne(['filialId' => $order->filial_id, 'closeAt' => null,'sort'=> 'new'])->getRepository()->getModel()->id;
            }

            return \App\Services\CRM\Courier\TransactionService::add([
                'filialCashBoxId' => $filialCashBoxId,
                'senderId'        => $order->courier_id,
                'orderId'         => $order->id,
                'transitType'     => TransactionTransitType::CourierTransit,
                'paymentType'     => (string)$order->payment_type,
                'price'           => $order->total_price/100,
                'quantityChecks'  => $order->payment_type->is(PaymentType::Terminal) ? 1 : null,
                'date'            => Carbon::now(),
                'operationType'   => TransactionOperationType::TopUpOrder,
                'status'          => TransactionStatus::Completed,
            ])->getRepository()->getModel();


        }

        throw new CourierServiceException('Ошибка валидации заказа, заказ не завершён!');

    }


    /**
     * @param int $userId
     * @return Collection
     * @throws RepositoryException
     */
    public function getTransactionsByCourierId(int $userId): Collection
    {
        $collection = $this->transactionRepository->findList([
            'senderId' => $userId,
            'status'  => TransactionStatus::Completed,
        ]);

        return $this->transactionRepository->getModelCollections($collection);
    }

    /**
     * @param int $userId
     * @param string $type
     * @return int
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function startTransaction(int $userId, string $type): int
    {
        try {

            /**
             * @var WorkSchedule $todayWorkSchedule
             * @var WorkSchedule $lastWorkSchedule
             * @var User $userModel
             */


            $userModel =  UserService::findOne(['id' => $userId])->getRepository()->getModel();

            $userModel->searchActualFilial();

            try {
                $filialCashBoxId = FilialCashBoxService::findOne(['filialId' => $userModel->actualFilial->id, 'day' => 'today','sort'=> 'new'])->getRepository()->getModel()->id;
            } catch (\Throwable $e) {
                $filialCashBoxId = FilialCashBoxService::findOne(['filialId' => $userModel->actualFilial->id, 'closeAt' => null,'sort'=> 'new'])->getRepository()->getModel()->id;
            }

        } catch (\Throwable $e) {
            throw new CourierServiceException('Выходной день, операция невозможна');
        }

        $newTransaction = $this->transactionRepository->add([
            'filialCashBoxId' => $filialCashBoxId,
            'date'            => Carbon::now(),
            'senderId'        => $userId,
            'operationType'   => $type,
            'status'          => TransactionStatus::New,
            'transitType'     => TransactionTransitType::CourierTransit,
        ]);

        CreateTransaction::dispatch($newTransaction->getModel(), $userModel);


        return $newTransaction->getModel()->id;
    }

    /**
     * @param int $userId
     * @param int $transactionId
     * @return Transaction
     * @throws CourierServiceException
     */
    public function getTransaction(int $userId, int $transactionId): Transaction
    {

        try {
            $transaction = $this->transactionRepository->findOne([
                'id'       => $transactionId,
                'senderId' => $userId,
            ]);

        } catch (\Throwable $e) {
            throw new CourierServiceException('Транзация не найдена');
        }

        return $transaction->getModel();
    }

    /**
     * @param int $userId
     * @param int $transactionId
     * @param array $data
     * @return Transaction
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function changeTransaction(int $userId, int $transactionId, array $data): Transaction
    {
        $transaction = $this->transactionRepository->findOne(['id' => $transactionId, 'senderId' => $userId]);

        if (array_key_exists('price', $data)) {
            $data['price'] *= 100;
        }
        $transaction->update($data);

        return $transaction->getModel()->refresh();
    }

    /**
     * @param int $userId
     * @param int $transactionId
     * @return Transaction
     * @throws CourierServiceException
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function confirmTransaction(int $userId, int $transactionId): Transaction
    {

        try {
            $transaction = $this->transactionRepository->findOne([
                'id'       => $transactionId,
                'senderId' => $userId,
                'status'   => TransactionStatus::Wait,
            ]);
        } catch (\Throwable $e) {
            throw new CourierServiceException('Невозможно потвердить данную транзакцию');
        }

        $transaction->update(['status' => TransactionStatus::Completed]);


        return $transaction->getModel()->refresh();
    }

    /**
     * @param int $userId
     * @param int $transactionId
     * @return Transaction
     * @throws CourierServiceException
     * @throws RepositoryException
     * @throws \Throwable
     */
    public function cancelTransaction(int $userId, int $transactionId): Transaction
    {
        try {
            $transaction = $this->transactionRepository->findOne([
                'id'       => $transactionId,
                'senderId' => $userId,
                '!status'  => TransactionStatus::Completed,
            ]);
        } catch (\Throwable $e) {
            throw new CourierServiceException('Невозможно отменить данную транзакцию');
        }

        $transaction->update(['status' => TransactionStatus::Cancel]);

        return $transaction->getModel()->refresh();
    }


    /**
     * @param int $userId
     * @return User
     * @throws RepositoryException
     */
    public function getBalance(int $userId): User
    {
        /**
         * @var UserRepository $courierRepo
         */
        $courierRepo = $this->userRepository->findOne(['id' => $userId]);
        $courierRepo->calcBalance();

        return $courierRepo->getModel();
    }

}
