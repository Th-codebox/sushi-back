<?php

namespace KKMClient\Models\Queries\Chunks;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\AccessType;
use KKMClient\Models\Queries\Chunks\PrintTextChunk;

/**
 * Class CheckString
 * @package KKMClient\Queries
 * @AccessType("public_method")
 */
class CheckString
{
    /**
     * @var \KKMClient\Models\Queries\Chunks\PrintTextChunk|null
     * @SerializedName("PrintText")
     * @Type("KKMClient\Models\Queries\Chunks\PrintTextChunk")
     */
    protected $printText;

    /**
     * @var \KKMClient\Models\Queries\Chunks\RegisterChunk|null
     * @SerializedName("Register")
     * @Type("KKMClient\Models\Queries\Chunks\RegisterChunk")
     */
    protected $register;

    /**
     * @var \KKMClient\Models\Queries\Chunks\BarcodeChunk|null
     * @SerializedName("BarCode")
     * @Type("KKMClient\Models\Queries\Chunks\BarcodeChunk")
     */
    protected $barcode;

    /**
     * @var \KKMClient\Models\Queries\Chunks\ImageChunk|null
     * @SerializedName("PrintImage")
     * @Type("KKMClient\Models\Queries\Chunks\ImageChunk")
     */
    protected $printImage;

    public function __construct () { }

    /**
     * @return \KKMClient\Models\Queries\Chunks\PrintTextChunk
     */
    public function getPrintText ()
    {
        return $this->printText;
    }

    /**
     * @param \KKMClient\Models\Queries\Chunks\PrintTextChunk $printText
     */
    public function setPrintText ( PrintTextChunk $printText )
    {
        $this->printText = $printText;
        return $this;
    }

    /**
     * @return RegisterChunk|null
     */
    public function getRegister ()
    {
        return $this->register;
    }

    /**
     * @param RegisterChunk|null $register
     */
    public function setRegister ( $register )
    {
        $this->register = $register;
    }

    /**
     * @return BarcodeChunk|null
     */
    public function getBarcode ()
    {
        return $this->barcode;
    }

    /**
     * @param BarcodeChunk|null $barcode
     */
    public function setBarcode ( $barcode )
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function getPrintImage(): ?ImageChunk
    {
        return $this->printImage;
    }

    public function setPrintImage(?ImageChunk $printImage): self
    {
        $this->printImage = $printImage;
        return $this;
    }
}
