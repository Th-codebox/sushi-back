<?php

namespace App\Observers\System;

use App\Models\System\FilialCashBox;
use App\Services\CRM\System\FilialCashBoxService;
use Illuminate\Support\Carbon;

class FilialCashBoxObserver
{
    /**
     * @param FilialCashBox $item
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function creating(FilialCashBox $item)
    {
        try {
            /**
             * @var FilialCashBoxService $prevWorkDay
             */
            $prevWorkDay = FilialCashBoxService::findOne(['filialId' => $item->filial_id, 'date' => Carbon::now()->subDay()->toDateString()]);

            $item->begin_cash = $prevWorkDay->getRepository()->getModel()->begin_cash;
            $item->begin_checks = $prevWorkDay->getRepository()->getModel()->begin_checks;
            $item->begin_terminal = $prevWorkDay->getRepository()->getModel()->begin_terminal;

        } catch (\Throwable $e) {

        }


    }

    /**
     * Handle the item "updated" event.
     *
     * @param FilialCashBox $item
     * @return void
     */
    public function updated(FilialCashBox $item)
    {
        //
    }

    /**
     * Handle the item "deleted" event.
     *
     * @param FilialCashBox $item
     * @return void
     */
    public function deleted(FilialCashBox $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param FilialCashBox $item
     * @return void
     */
    public function restored(FilialCashBox $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param FilialCashBox $item
     * @return void
     */
    public function forceDeleted(FilialCashBox $item)
    {
        //
    }
}
