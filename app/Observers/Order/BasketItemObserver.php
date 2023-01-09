<?php

namespace App\Observers\Order;

use App\Models\Order\BasketItem;
use App\Repositories\Order\BasketItemRepository;
use App\Services\CRM\Order\BasketService;

class BasketItemObserver
{


  // /**
  //  * @param BasketItem $item
  //  */
  // public function creating(BasketItem $item) : void
  // {

  //     $item->calcTotalPrice();
  // }

  // /**
  //  * @param BasketItem $item
  //  */
  // public function updating(BasketItem $item) : void
  // {

  //     $item->calcTotalPrice();
  // }


    /**
     * @param BasketItem $item
     */
    public function deleted(BasketItem $item)
    {
        //
    }

    /**
     * @param BasketItem $item
     */
    public function restored(BasketItem $item)
    {
        //
    }

    /**
     * @param BasketItem $item
     */
    public function forceDeleted(BasketItem $item)
    {
        //
    }
}
