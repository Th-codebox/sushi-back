<?php

namespace App\Observers;

use App\Models\Store\Filial;

class FilialObserver
{
    /**
     * Handle the item "created" event.
     *
     * @param  Filial $item
     * @return void
     */
    public function created(Filial $item)
    {
        //
    }

    /**
     * Handle the item "updated" event.
     *
     * @param  Filial $item
     * @return void
     */
    public function updated(Filial $item)
    {
        //
    }

    /**
     * Handle the item "deleted" event.
     *
     * @param  Filial $item
     * @return void
     */
    public function deleted(Filial $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param  Filial $item
     * @return void
     */
    public function restored(Filial $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param  Filial $item
     * @return void
     */
    public function forceDeleted(Filial $item)
    {
        //
    }
}
