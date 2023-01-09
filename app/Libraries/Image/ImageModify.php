<?php


namespace App\Libraries\Image;


use App\Libraries\ImageUpload;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


/**
 * Class ImageModify
 * @package App\Libraries\Image
 */
class ImageModify implements ImageModifyInterface
{

    private $mimeTypes = [
        'image/gif'     => 'gif',
        'image/jpeg'    => 'jpg',
        'image/pjpeg'   => 'jpg',
        'image/png'     => 'png',
        'image/svg+xml' => 'svg',
        'image/svg'     => 'svg',
    ];

    private static $_instance;

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @return ImageModify|null
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof ImageUpload)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * @param string|null $path
     * @param int|null $width
     * @param int|null $height
     * @return \Intervention\Image\Image|mixed|null
     */
    public function fit(?string $path, int $width = null, int $height = null)
    {
        try {
            $absolutePath = $this->getAbsolutePath($path);

            $ext = $this->mimeTypes[mime_content_type($absolutePath)];

            if (!$width && !$height) {

                [$_width, $_height] = getimagesize($absolutePath);

                $width = $_width;
                $height = $_height;

                $postfix = 'origin_size';

            } else if (!$width) {

                [$_width, $_height] = getimagesize($absolutePath);

                $width = round($_width / ($_height / $height));

                $postfix = 'height_' . $height;

            } else if (!$height) {

                [$_width, $_height] = getimagesize($absolutePath);

                $height = round($_height / ($_width / $width));

                $postfix = 'width_' . $width;

            } else {
                $postfix = $width . 'x' . $height;
            }

            $newPath =  substr($path, 0, strrpos($path, '.')) . '-fit-' . $postfix . '.' . $ext;

            if (!Storage::disk('image')->exists($newPath)) {


                $img = Image::make($absolutePath);

                $imageModify = $img->fit($width, $height);

                $imageModify->save($this->getAbsolutePath($newPath));
            }


        } catch (\Throwable $e) {
            return null;
        }


        return $this->getHttpPath($newPath);
    }

    /**
     * @param string|null $path
     * @param int|null $width
     * @param int|null $height
     * @return \Intervention\Image\Image|mixed
     */
    public function resize(?string $path, int $width = null, int $height = null)
    {
        try {
            $absolutePath = $this->getAbsolutePath($path);

            $ext = $this->mimeTypes[mime_content_type($absolutePath)];

            if (!$width && !$height) {

                [$_width, $_height] = getimagesize($absolutePath);

                $width = $_width;
                $height = $_height;

                $postfix = 'origin_size';

            } else if (!$width) {

                [$_width, $_height] = getimagesize($absolutePath);

                $width = round($_width / ($_height / $height));

                $postfix = 'height_' . $height;

            } else if (!$height) {

                [$_width, $_height] = getimagesize($absolutePath);

                $height = round($_height / ($_width / $width));

                $postfix = 'width_' . $width;

            } else {
                $postfix = $width . 'x' . $height;
            }

            $newPath =  substr($path, 0, strrpos($path, '.')) . '-resize-' . $postfix . '.' . $ext;

            if (!Storage::disk('image')->exists($newPath)) {


                $img = Image::make($absolutePath);

                $imageModify = $img->resize($width, $height);

                $imageModify->save($this->getAbsolutePath($newPath));
            }


        } catch (\Throwable $e) {
            return null;
        }


        return $this->getHttpPath($newPath);
    }

    public function getAbsolutePath(string $path)
    {
        return Storage::disk('image')->path($path);
    }
    public function getHttpPath(string $path)
    {
        return Storage::disk('image')->url($path);
    }
}
