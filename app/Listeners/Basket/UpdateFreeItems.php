<?php

namespace App\Listeners\Basket;

use App\Enums\BasketItemType;
use App\Enums\MenuItemType;
use App\Enums\PromoCodeAction;
use App\Events\Basket\UpdateBasket;
use App\Repositories\Order\BasketItemRepository;
use App\Services\CRM\Order\BasketItemService;

class UpdateFreeItems
{

    /**
     * @param UpdateBasket $event
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     * @throws \Throwable
     */
    public function handle(UpdateBasket $event): void
    {

        $clientPromoCode = $event->basket->clientPromoCode;

        $basketItems = BasketItemService::findList(['basketId' => $event->basket->id]);

        $refreshFreeItems = [];

        foreach ($basketItems as $basketItem) {

            /**
             * @var BasketItemService $basketItem
             */
            if ($basketItem->getRepository()->isFree()) {
                $basketItem->delete();
                continue;
            }

            if ($clientPromoCode && (string)$clientPromoCode->promoCode->action === PromoCodeAction::Doubling) {

                $copyBasketItem = $basketItem->getRepository()->getArray();
                $copyBasketItem['free'] = true;
                $refreshFreeItems[] = $copyBasketItem;
            }
        }

        if ($clientPromoCode) {
            if ((string)$clientPromoCode->promoCode->action === PromoCodeAction::DishGift) {

                $refreshFreeItems[] = [
                    'basketId'               => $event->basket->id,
                    'menuItemId'             => $clientPromoCode->promoCode->sale_menu_item_id,
                    'modificationMenuItemId' => $clientPromoCode->promoCode->sale_modification_menu_item_id,
                    'price'                  => 0,
                    'free'                   => true,
                    'type'                   => BasketItemType::Usual,
                ];
            }

            foreach ($refreshFreeItems as $refreshFreeItem) {
                (new BasketItemRepository)->add($refreshFreeItem);
            }
        }

    }
}
