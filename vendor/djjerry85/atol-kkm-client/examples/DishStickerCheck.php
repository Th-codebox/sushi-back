<?php

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

class DishStickerCheck extends AbstractCheck
{
    const LINE_LENGTH = 39;
    protected $deviceNumber = 1;

    public function getTemplate(): CheckStringsBuilder
    {
        return ( new CheckStringsBuilder() )
            ->setLineLength(static::LINE_LENGTH)

            ->textCenter("Запеченный ролл креветка в темпуре", 2)
            ->emptyLines(1)
            ->twoColsText(["приготовлено", date('d.m.Y    H:i')])
            ->lineSeparator('-')
            ->text("Состав: Лосось, рис, водоросли нори, «Снежный краб» (фарш рыбный сурими, майонез), огурец, соус унаги, розовый соус (майонез, икра масаго, чеснок, кетчуп, соус табаско), кунжут белый.")
            ->lineSeparator('-')
            ->twoColsText(["вес: 300г",  "срок хранения: 12ч"])
            ->emptyLines(1)
            ->colsCentered(["573", "33", "27", "21"])
            ->colsCentered(["ккал.", "жиры", "углеводы", "белки"])
            ->emptyLines(1)
            ->textCenter("Заказ 1210-1", 2);
    }


    public function isPrintable(): bool
    {
        // Проверка нужно ли печатать чек
        return true;
    }

}
