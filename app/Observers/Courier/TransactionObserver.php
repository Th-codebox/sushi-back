<?php

namespace App\Observers\Courier;


use App\Enums\TransactionPaymentType;
use App\Enums\TransactionOperationType;
use App\Models\System\Transaction;
use App\Models\Order\Order;
use App\Models\System\User;
use App\Services\CRM\System\UserService;


class TransactionObserver
{
    /**
     * @param Transaction $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function creating(Transaction $item)
    {

    }


    /**
     * @param Transaction $item
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function saving(Transaction $item)
    {

        /**
         * @var User $userModel
         */
        $userModel = UserService::findOne(['id' => $item->sender_id])->getRepository()->getModel();

        $userModel->calcBalance();

        $balance = (string)$item->payment_type === TransactionPaymentType::Cash ? $userModel->cashBalance : $userModel->terminalBalance;

        $item->balance_before = $balance;

        $diff = $item->price;

        if ((string)$item->operation_type === TransactionOperationType::Send) {
            $diff = 0 - $item->price;
        }

        $item->balance_after = $balance + $diff;

    }

    public function saveActiveLog()
    {

    }

    /**
     * Handle the item "deleted" event.
     *
     * @param Order $item
     * @return void
     */
    public function deleted(Order $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param Order $item
     * @return void
     */
    public function restored(Order $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param Order $item
     * @return void
     */
    public function forceDeleted(Order $item)
    {
        //
    }
}
