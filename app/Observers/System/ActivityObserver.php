<?php

namespace App\Observers\System;

use App\Models\Order\Basket;
use App\Models\System\Activity;
use App\Repositories\System\UtmRepository;

class ActivityObserver
{
    /**
     * @param Activity $item
     */
    public function created(Activity $item)
    {

        $properties = $item->getProperties();
        if (array_key_exists('utms', $properties) && is_array($properties['utms'])) {

            $newUtm = (new UtmRepository())->add($properties['utms']);

            $item->utm_id = $newUtm->getId();

            $item->save();
        }
    }

    /**
     * @param Activity $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function updated(Activity $item)
    {

    }

    /**
     * Handle the item "deleted" event.
     *
     * @param Basket $item
     * @return void
     */
    public function deleted(Activity $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param Basket $item
     * @return void
     */
    public function restored(Activity $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param Basket $item
     * @return void
     */
    public function forceDeleted(Activity $item)
    {
        //
    }
}
