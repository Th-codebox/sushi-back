<?php


namespace App\Libraries\Image;


interface ImageModifyInterface
{

    /**
     * @param $path
     * @param int|null $width
     * @param int|null $height
     * @return mixed
     */
    public function resize(string $path,int $width = null,int $height = null);


    /**
     * @param $path
     * @param int|null $width
     * @param int|null $height
     * @return mixed
     */
    public function fit(string $path,int $width = null,int $height = null);
}
