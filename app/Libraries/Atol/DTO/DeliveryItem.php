<?php


namespace App\Libraries\Atol\DTO;


use App\Models\Order\BasketItem;
use App\Models\Order\Order;
use ItQuasar\AtolOnline\Item;
use ItQuasar\AtolOnline\PaymentMethod;
use ItQuasar\AtolOnline\PaymentObject;
use ItQuasar\AtolOnline\Vat;

class DeliveryItem extends Item
{

    public static function fromOrder(Order $order, Vat $vat) : self
    {
        $name = 'Доставка';

        return (new static($name))
            ->setPrice($order->delivery_price/100)
            ->setQuantity(1)
            ->setSum($order->delivery_price/100)
            ->setVat($vat)
            ->setPaymentObject(PaymentObject::SERVICE)
            ->setPaymentMethod(PaymentMethod::FULL_PAYMENT);
    }
}
