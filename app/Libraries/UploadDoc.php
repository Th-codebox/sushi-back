<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class TempDoc
 * @package App\Libraries
 */
class UploadDoc
{
    private static $_instance = null;

    private static array $mimeTypes = [
        'docx' => [
            'application/vnd.ms-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'doc'  => [
            'application/msword',
            'application/vnd.ms-word',
        ],
        'dot'  => 'application/msword',
        'odt'  => 'application/vnd.oasis.opendocument.text',
        'odx'  => [
            'application/vnd.oasis.opendocument.text',
        ],
        'pdf'  => 'application/pdf',
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
        'xml'  => 'application/vnd.ms-excel',
        'xls'  => [
            'application/vnd.ms-excel',
            'application/excel',
            'application/msexcel',
            'application/msexcell',
            'application/x-dos_ms_excel',
            'application/x-excel',
            'application/x-ms-excel',
            'application/x-msexcel',
            'application/x-xls',
            'application/xls',
        ],
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'txt'  => 'text/plain',
    ];

    protected function __construct()
    {
        //
    }

    private function __clone()
    {
        //
    }

    private function __wakeup()
    {
        //
    }

    /**
     * @return UploadDoc|null
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof UploadDoc)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param Request $request
     * @param string $path
     * @return array
     * @throws \Exception
     */
    public static function upload(Request $request, string $path = ''): array
    {
        $file = $request->file('file');

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

        if (strpos($ext, 'php') !== false) {
            throw new \Exception('Bad idea, f*ck u.');
        }

        if ($ext !==  null) {

            $fileName = substr(md5(mt_rand(0, 9999) . time()), 0, 8) . '.' . $ext;

           if ($path !== '') {

                $path = str_replace('.', '', $path);

                $uploadedFileDir = trim($path, " \t\n\r \v /");

                $file->storeAs($uploadedFileDir, $fileName, 'doc');

            } else {
                throw new \Exception('Ошибка загрузки файла: путь должен быть указан');
            }


            $fileUrl = Storage::disk('doc')->url($uploadedFileDir . '/' . $fileName);


            return [
                'fullPath' => $uploadedFileDir . '/' . $fileName,
                'url'      => $fileUrl,
            ];
        } else {
            return [
                'message' => 'Ошибка формата, проверьте формат файла или обратитесь к разработчику',
                'status'  => false,
            ];
        }
    }
}
