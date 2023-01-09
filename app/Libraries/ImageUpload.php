<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImageUpload
 * @package App\Libraries
 */
class ImageUpload
{
    private static $_instance = null;

    private static array $mimeTypes = [
        'gif'  => 'image/gif',
        'jpg'  => [
            'image/jpeg',
            'image/jpg',
        ],
        'webp' => 'image/webp',
        'png'  => 'image/png',
        'svg'  => [
            'image/svg',
            'image/svg+xml',
        ],
    ];

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
     * @return ImageUpload
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof ImageUpload)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * @param Request $request
     * @param string $path
     * @return array
     * @throws \Exception
     */
    public static function upload(Request $request, string $path): array
    {
        $file = $request->file('image');

        $ext = null;

        $mimeType = $file->getMimeType();
        $extOrigin = $file->getClientOriginalExtension();

        foreach (self::$mimeTypes as $mimeTypeExt => $mimeTypes) {
            if ((is_array($mimeTypes) && in_array(strtolower($mimeType), $mimeTypes)) || (is_string($mimeTypes) && strtolower($mimeType) === $mimeTypes)) {
                if (strtolower($extOrigin) === strtolower($mimeTypeExt)) {
                    $ext = $mimeTypeExt;
                    break;
                } else {
                    $ext = $mimeTypeExt;
                }
            }
        }

        if ($ext !== null) {

            if (strpos($ext, 'php') !== false) {
                throw new \Exception('Bad idea, f*ck u.');
            }

            $fileName = substr(md5(mt_rand(0, 9999) . time()), 0, 8) . '.' . $ext;

            if ($path !== '') {

                $path = str_replace('.', '', $path);

                $fileDir = trim($path, " \t\n\r \v /");

                $file->storeAs($fileDir, $fileName, 'image');

            } else {
                throw new \Exception('Ошибка загрузки изображения: путь должен быть указан');
            }

            $imageUrl = Storage::disk('image')->url($fileDir . '/' . $fileName);

            return [
                'image' => $fileDir . '/' . $fileName,
                'path'  => $imageUrl,
            ];
        } else {
            return [
                'message' => 'Ошибка загрузки изображения: неверный формат',
                'status'  => false,
            ];
        }
    }
}
