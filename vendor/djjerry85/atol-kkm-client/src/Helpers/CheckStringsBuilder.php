<?php


namespace KKMClient\Helpers;


use KKMClient\Exceptions\BadConfigException;
use KKMClient\Models\Queries\Chunks\BarcodeChunk;
use KKMClient\Models\Queries\Chunks\CheckString;
use KKMClient\Models\Queries\Chunks\ImageChunk;
use KKMClient\Models\Queries\Chunks\PrintTextChunk;

class CheckStringsBuilder
{
    protected $lines;
    protected $allowedSeparatorSymbols = [".", "-", "*"];
    protected $lineLength = 40;

    public function getStrings()
    {
        return $this->lines;
    }

    /**
     * @param int $lineLength
     */
    public function setLineLength(int $lineLength): self
    {
        $this->lineLength = $lineLength;
        return $this;
    }


    /**
     * @param string $text
     * @param int $font
     * @param int $intensity
     * @return $this
     */
    public function text(string $text, int $font = 0, $intensity = 0): self
    {
        $textChunk = (new PrintTextChunk())
            ->setText($text)
            ->setFont($font)
            ->setIntensity($intensity);
        $this->lines[] = (new CheckString())->setPrintText($textChunk);
        return $this;
    }

    /**
     * @param $text
     * @param int $font
     * @param int $intensity
     * @return $this
     */
    public function textCenter(string $text, $font = 0, $intensity = 0): self
    {
        return $this->text(">#2#<" . $text, $font, $intensity);
    }

    public function twoColsText(array $cols, $font = 0, $intensity = 0): self
    {
        if (count($cols) != 2) {
            throw new BadConfigException("Параметр cols должен быть массивом с двумя элементами");
        }
        $this->text(implode(" <#0#> ", $cols), $font, $intensity);
        return $this;
    }

    /**
     * @param string $textContent
     * @return $this
     */
    public function qrCode($textContent): self
    {
        $barcode = new BarcodeChunk();
        $barcode->setBarcode($textContent);
        $barcode->setBarcodeType('QR');
        $s = (new CheckString())->setBarcode($barcode);

        $this->lines[] = $s;
        return $this;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function emptyLines($count = 1): self
    {
        for ($i=0; $i<$count; $i++) {
            $this->text(" ");
        }
        return $this;
    }

    /**
     * @param string $symbol
     * @return $this
     */
    public function lineSeparator($symbol = '-'): self
    {
        if (!in_array($symbol, $this->allowedSeparatorSymbols)) {
            //throw new BadConfigException("Символ '{$symbol}' не разрешен для использования в качестве линии-разделителя");
        }
        $this->text("<<{$symbol}>>");
        return $this;
    }

    /**
     * @param string $contentBase64
     * @return $this
     */
    public function image(string $contentBase64): self
    {
        $image = ( new ImageChunk() )
            ->setImage($contentBase64);
        $this->lines[] = (new CheckString())->setPrintImage($image);
        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function colsCentered(array $columns): self
    {
        $colsCount = count($columns);
        $colLength = $this->lineLength / $colsCount;

        foreach ($columns as &$col) {
            $curColLength = mb_strlen($col, "utf-8");

            for ($i = $curColLength; $i <= $colLength; $i++ ) {
                if ($i % 2 == 0) {
                    $col .= ' ';
                } else {
                    $col = ' ' . $col;
                }
            }
        }

        $str = implode('', $columns);

        return $this->text($str);
    }


}
