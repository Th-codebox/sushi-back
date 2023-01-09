<?php

namespace App\Observers\System;


use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Libraries\Helpers\SettingHelper;
use App\Models\System\Client;
use App\Services\CRM\Store\PromoCodeService;
use App\Services\CRM\System\ClientService;


class ClientObserver
{
    /**
     * @param Client $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function creating(Client $item)
    {
        $item->referral_promo_code = 'FOX' . random_int(1000, 9999) . (ClientService::getLastId() + 1);

    }

    /**
    /**
     * @param Client $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function created(Client $item)
    {

    }

    /**
     * @param Client $item
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function updated(Client $item)
    {


    }

    public function saveActiveLog()
    {

    }

    /**
     * Handle the item "deleted" event.
     *
     * @param Client $item
     * @return void
     */
    public function deleted(Client $item)
    {
        //
    }

    /**
     * Handle the item "restored" event.
     *
     * @param Client $item
     * @return void
     */
    public function restored(Client $item)
    {
        //
    }

    /**
     * Handle the item "force deleted" event.
     *
     * @param Client $item
     * @return void
     */
    public function forceDeleted(Client $item)
    {
        //
    }
}
