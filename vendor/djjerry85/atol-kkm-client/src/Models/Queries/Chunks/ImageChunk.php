<?php

namespace KKMClient\Models\Queries\Chunks;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\AccessType;


/**
 * Class ImageChunk
 * @package KKMClient\Queries\Chunks
 * @AccessType("public_method")
 */
class ImageChunk
{

    /**
     * @var string
     * @SerializedName("Image")
     * @Type("string")
     */
    protected $image;


    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }
}
