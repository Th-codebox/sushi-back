<?php


namespace App\Http\Controllers\Courier\Order;


use App\Http\Controllers\Controller;
use App\Http\Requests\Courier\OrderCourier\TransactionStart;
use App\Http\Requests\Courier\OrderCourier\UpdateTransaction;
use App\Http\Resources\Courier\CourierBalanceResource;
use App\Http\Resources\Courier\TransactionResource;
use App\Services\Courier\TransactionService;


class MoneyController extends Controller
{
    protected string $resource = TransactionResource::class;
    protected TransactionService $transactionService;


    public function __construct( TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function getBalance()
    {
        $balance = $this->transactionService->getBalance(auth()->user()->id);

        $this->resource = CourierBalanceResource::class;

        return $this->respondWithItem($balance);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function transactions()
    {
        $transactions = $this->transactionService->getTransactionsByCourierId(auth()->user()->id);

        return $this->respondWithCollection($transactions);
    }


    /**
     * @param int $id
     * @return mixed
     * @throws \App\Services\Courier\CourierServiceException
     */
    public function getTransaction(int $id)
    {

        $transactions = $this->transactionService->getTransaction(auth()->user()->id, $id);


        return $this->respondWithItem($transactions);
    }

    /**
     * @param TransactionStart $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function transactionStart(TransactionStart $request)
    {
        $data = $request->all();

        $newTransactionId = $this->transactionService->startTransaction(auth()->user()->id, $data['type']);

        return $this->responseSuccess(['success' => true, 'id' => $newTransactionId]);
    }

    /**
     * @param int $id
     * @param UpdateTransaction $request
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     */
    public function transactionChange(int $id, UpdateTransaction $request)
    {
        $data = $request->all();
        $transactions = $this->transactionService->changeTransaction(auth()->user()->id, $id, $data);

        return $this->respondWithItem($transactions);
    }


    /**
     * @param int $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function transactionConfirm(int $id)
    {
        $transactions = $this->transactionService->confirmTransaction(auth()->user()->id, $id);

        return $this->respondWithItem($transactions);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function transactionCancel(int $id)
    {
        $transactions = $this->transactionService->cancelTransaction(auth()->user()->id, $id);

        return $this->respondWithItem($transactions);
    }




}
