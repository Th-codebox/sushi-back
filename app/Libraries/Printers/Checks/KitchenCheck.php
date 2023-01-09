<?php

namespace App\Libraries\Printers\Checks;


use App\Enums\BasketSource;
use App\Libraries\DTO\BasketGroupItem;
use App\Libraries\Helpers\MoneyHelper;
use Carbon\CarbonTimeZone;
use KKMClient\Helpers\CheckStringsBuilder;


class KitchenCheck extends BaseCheck
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
        $createdAt = $order->created_at->tz($timeZone)->format("H:i");

        $deadLine = $order->dead_line->tz($timeZone)->format("H:i");



        $template = ( new CheckStringsBuilder() )
            ->setLineLength(static::LINE_LENGTH);

        $this->addHeader($template);

        $template
            ->textCenter("Заказ")
            ->textCenter("*** {$this->getOrderNumber()} ***", 1, 1);

        $this->addSourceLabel($template);

        $template
            ->emptyLines(2)

            ->twoColsText(["Состав заказа", "принят: ".$createdAt]); //"12.03.2020 12:00"


        $this->addProducts($template);


        $template
            ->twoColsText([ "Доставить до:", $deadLine])
            ->emptyLines(3);


        return $template;
    }

    protected function addHeader(CheckStringsBuilder $template): void
    {
        $template
            ->textCenter("СУШИФОКС", 1, 15)
            ->textCenter("Вкусно & Быстро", 3, 15)
            ->emptyLines(1)
            ->textCenter("8 (812) 338 - 1111", 3, 15)
            ->textCenter("www.sushifox.ru", 3, 15)
            ->emptyLines(1)
            ->textCenter("*****", 3, 15)
            ->emptyLines(4);
    }



    protected function formatProductLine(BasketGroupItem $item, CheckStringsBuilder $template): void
    {
        $name = $item->menuItemName;
        $modificator = "";

        if ($item->modificationMenuItemNameOn == "половинка") {
            $name .= "  1/2 ";
        } elseif ($item->modificationMenuItemNameOn == "добавить сырный бортик") {
            $name .= " + СБ ";
        } else {
            $modificator = $item->modificationMenuItemNameOn;
        }

        $template->twoColsText([$name, "    x{$item->quantity}"], 2);

        if ($modificator) {
            $template->text("мод. {$modificator}", 3);
        }

        $template->emptyLines(1);
    }


}
