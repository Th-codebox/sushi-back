<?php

namespace App\Libraries\Printers\Checks;


use App\Libraries\DTO\BasketGroupItem;
use App\Libraries\Helpers\MoneyHelper;
use Carbon\CarbonTimeZone;
use KKMClient\Helpers\CheckStringsBuilder;


class ClientMainCheck extends BaseCheck
{
    const LINE_LENGTH = 39;
    //protected $deviceNumber = 2;


    /**
     * Проверка нужно ли печатать чек
     */
    public function isPrintable(): bool
    {
        return true;
    }


    public function getTemplate(): CheckStringsBuilder
    {
        $order = $this->order;

        $timeZone = $this->filialSettings->getTimeZone($order->filial_id);
        $createdAt = $order->created_at->tz($timeZone)->format("d.m.Y H:i");

        if ($order->client->phone && $order->client->name) {
            $thankYouText = "{$order->client->name} , приятного аппетита!";
        } else {
            // Анонимный клиент
            $thankYouText = "Приятного аппетита!";
        }


        $template = ( new CheckStringsBuilder() )
            ->setLineLength(static::LINE_LENGTH);

        $this->addHeader($template);

        $template
            ->textCenter("Заказ")
            ->textCenter("*** {$this->getOrderNumber()} ***", 1, 1);

            $this->addSourceLabel($template);

        $template
            ->emptyLines(2)
            
            ->textCenter("Кол-во персон: {$order->basket->persons}")
            ->emptyLines(1)

            ->twoColsText(["Состав заказа", $createdAt]); //"12.03.2020 12:00"


        $this->addProducts($template);

        $this->addDiscountLine($template);

        $template
            ->twoColsText([ "К оплате:", $order->getTotalPriceInRub() . " р." ], 2, 14)
            ->emptyLines(1)
            ->twoColsText([ "Тип оплаты:", $order->payment_type->description ])
            ->emptyLines(3);

        if ($order->clientAddress) {
            $template
                ->textCenter("Адрес")
                ->emptyLines(1)
                ->text('>#0#<'.$order->clientAddress->asFormattedString(), 3)
                ->emptyLines(3);
        }


        $template
            ->textCenter($thankYouText)
            ->emptyLines(5);

        $this->addSiteLinkQRCode($template);

        return $template;
    }



    protected function formatProductLine(BasketGroupItem $item, CheckStringsBuilder $template): void
    {
        $name = $item->menuItemName;

        if ($item->quantity > 1) {
            $name .= "  x{$item->quantity}";
        }

        $maxNameLength = static::LINE_LENGTH - 6;


        if (mb_strlen($name, 'UTF-8') >= $maxNameLength) {
            $name1 = wordwrap($name, $maxNameLength, "====");
            $lines = explode("====", $name1);
            $name = array_pop($lines);

            foreach ($lines as $nameLine) {
                $template->text($nameLine);
            }
        }

        $price = MoneyHelper::format($item->price);
        $template->twoColsText([$name, "{$price}р."]);

        if ($item->modificationMenuItemNameOn) {
            $template->text("мод. {$item->modificationMenuItemNameOn}", 4);
        }

        $template->emptyLines(1);
    }

    protected function addDiscountLine(CheckStringsBuilder $template): void
    {
        $order = $this->order;

        if ($order->discount_amount) {
            $discountName = "Скидка ";
            $discountInRub = MoneyHelper::format($order->discount_amount);

            if ($discountPercent = $order->getDiscountPercent()) {
                $discountName .= "{$discountPercent}% ";
            }

            $template->twoColsText([$discountName, " {$discountInRub}р."]);
        }
    }

}
