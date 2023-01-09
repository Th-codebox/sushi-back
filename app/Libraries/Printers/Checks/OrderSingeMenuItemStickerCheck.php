<?php
namespace App\Libraries\Printers\Checks;


use App\Libraries\Printers\Exceptions\PrinterException;
use App\Models\Domain\Store\SingleMenuItem;
use App\Models\Domain\Store\TechnicalCardCalculator;
use App\Models\Order\Order;
use KKMClient\Helpers\CheckStringsBuilder;
use KKMClient\Interfaces\CommandInterface;
use KKMClient\Models\AbstractCheck;
use KKMClient\Models\Queries\Chunks\BarcodeChunk;
use KKMClient\Models\Queries\Chunks\CheckString;
use KKMClient\Models\Queries\Chunks\ImageChunk;
use KKMClient\Models\Queries\Chunks\PrintTextChunk;
use KKMClient\Models\Queries\Chunks\RegisterChunk;
use KKMClient\Models\Queries\Commands\RegisterCheck;
use KKMClient\Models\Queries\Enums\CheckTypes;

class OrderSingeMenuItemStickerCheck extends BaseCheck
{
    const LINE_LENGTH = 39;
    //protected $deviceNumber = 1;

    protected Order $order;
    protected SingleMenuItem $singleMenuItem;
    protected TechnicalCardCalculator $technicalCardCalculator;

    /**
     * OrderSingeMenuItemStickerCheck constructor.
     * @param Order $order
     * @param SingleMenuItem $singleMenuItem
     */
    public function __construct(Order $order, SingleMenuItem $singleMenuItem)
    {
        $this->order = $order;
        $this->singleMenuItem = $singleMenuItem;
        $this->technicalCardCalculator = new TechnicalCardCalculator(
            $singleMenuItem->menuItem,
            $singleMenuItem->modification
        );
    }


    public function getTemplate(): CheckStringsBuilder
    {
        $product = $this->singleMenuItem->menuItem;
        $modification = $this->singleMenuItem->modification;

        $techCard = $this->technicalCardCalculator->getActualTechCard();


        $template =  ( new CheckStringsBuilder() )
            ->setLineLength(static::LINE_LENGTH)

            ->textCenter($product->name, 2);

        if ($modification) {
            $template->textCenter("мод. {$modification->modification->name}");
        }

        $template
            ->emptyLines(2)
            ->twoColsText(["приготовлено", date('d.m.Y   H:i')])
            //->emptyLines(1)
            ->lineSeparator('-')
            ->text("Состав: {$product->composition}")
            ->lineSeparator('-')
            ->emptyLines(1)
            ->twoColsText(["вес: {$techCard->weight}г",  "срок хранения: 12ч"])
            ->emptyLines(1)
            ->colsCentered([
                $techCard->calories,
                $techCard->fats,
                $techCard->carbohydrates,
                $techCard->proteins ])
            ->colsCentered(["ккал.", "жиры", "углеводы", "белки"])
            ->emptyLines(2)
            ->textCenter("Заказ {$this->getOrderNumber()}", 1);

        return $template;
    }


    public function isPrintable(): bool
    {
        // Проверка нужно ли печатать чек
        return true;
    }

    protected function formatProductLine(\App\Libraries\DTO\BasketGroupItem $item, CheckStringsBuilder $template): void
    {
        throw new LogicException("Метод не поодерживается в этом чеке");
    }
}
