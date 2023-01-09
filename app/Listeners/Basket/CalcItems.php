<?php

namespace App\Listeners\Basket;

use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use App\Enums\PromoCodeAction;
use App\Events\Basket\UpdateBasket;
use App\Libraries\Helpers\SettingHelper;
use App\Repositories\Order\BasketItemRepository;
use App\Repositories\Order\BasketRepository;
use App\Services\CRM\Order\BasketService;

class CalcItems
{
    /**
     * @param UpdateBasket $event
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function handle(UpdateBasket $event): void
    {

        /**
         * @var BasketRepository $basketRepo
         */
        $basketRepo = BasketService::findOne(['id' => $event->basket->id])->getRepository();

        $saveData['totalPrice'] = 0;
        $saveData['discountAmount'] = 0;

        foreach ($basketRepo->getModel()->items as $basketItem) {

            /**
             * @var BasketItemRepository $basketItemRepo
             */
            $basketItemRepo = new BasketItemRepository($basketItem);
            if (!$basketItemRepo->isFree()
                || ($basketItemRepo->getModel()->basket->clientPromoCode &&
                    $basketItemRepo->getModel()->basket->clientPromoCode->promoCode === PromoCodeAction::Doubling
                )) {
                $basketItemRepo->getModel()->calcTotalPrice();
                $saveData['totalPrice'] += $basketItemRepo->getPrice();
                $basketItemRepo->update(['price' => $basketItemRepo->getPrice()]);
            }
        }

        if ($saveData['totalPrice'] / 100 > SettingHelper::getSettingValue('totalForPreBay', $basketRepo->getModel()->filial_id)) {
            $saveData['paymentType'] = PaymentType::Online;
        }

        $clientPromoCode = $basketRepo->getModel()->clientPromoCode;

        if ($clientPromoCode) {

            if ((string)$clientPromoCode->promoCode->action === PromoCodeAction::Subtract) {

                $saveData['totalPrice'] -= (int)$clientPromoCode->promoCode->sale_subtraction;
                $saveData['discountAmount'] += (int)$clientPromoCode->promoCode->sale_subtraction;

            } elseif ((string)$clientPromoCode->promoCode->action === PromoCodeAction::Percent
                || (string)$clientPromoCode->promoCode->action === PromoCodeAction::FriendPercent
                || (string)$clientPromoCode->promoCode->action === PromoCodeAction::BirthDay) {

                $salePercentInMoney = ($saveData['totalPrice'] / 100) * $clientPromoCode->promoCode->sale_percent;

                $saveData['totalPrice'] -= $salePercentInMoney;
                $saveData['discountAmount'] += (int)$salePercentInMoney;

            }
            if ((string)$clientPromoCode->promoCode->action === PromoCodeAction::Doubling) {
                $saveData['discountAmount'] += $saveData['totalPrice'] ?? 0;
            }
        }


        if ($saveData['totalPrice'] > 0
            && $saveData['totalPrice'] < (int)$basketRepo->getModel()->free_delivery
            && $basketRepo->getModel()->delivery_type === DeliveryType::Delivery) {

            $saveData['totalPrice'] += (int)$basketRepo->getModel()->delivery_price;

        }


        $basketRepo->update($saveData);
    }
}
