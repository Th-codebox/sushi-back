<?php


namespace App\Libraries\Atol\DTO;


use App\Models\Order\BasketItem;
use ItQuasar\AtolOnline\Item;
use ItQuasar\AtolOnline\PaymentMethod;
use ItQuasar\AtolOnline\PaymentObject;
use ItQuasar\AtolOnline\Vat;

class FiscalItem extends Item
{
    public string $uuid;

    public function createUuid(BasketItem $basketItem) :self
    {
        $this->uuid = $basketItem->free
            . '-' . ($basketItem->menuItem->id ?? 0)
            . '-' . ($basketItem->modificationMenuItem->id ?? 0)
            . '-' . ($basketItem->subMenuItem->id ?? 0);

        return $this;
    }

    public function addQuantity() : self
    {
        $this->setQuantity($this->getQuantity() + 1);
        $this->calcSum();
        return $this;
    }

    public function calcSum() : self
    {
        $this->setSum($this->getPrice() * $this->getQuantity());
        return $this;
    }

    public static function fromBasketItem(BasketItem $basketItem, Vat $vat) : self
    {
        $name = $basketItem->menuItem->name;

        if ($basketItem->modificationMenuItem) {
            $name .= ', ' . $basketItem->modificationMenuItem->modification->name_on;
        }

        return (new static($name))
            ->createUuid($basketItem)
            ->setPrice($basketItem->price/100)
            ->setQuantity(1)
            ->calcSum()
            ->setVat($vat)
            ->setPaymentObject(PaymentObject::COMMODITY)
            ->setPaymentMethod(PaymentMethod::FULL_PAYMENT);
           // ->setMeasurementUnit('шт.');;
    }
}
