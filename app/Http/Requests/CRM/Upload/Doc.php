<?php


namespace App\Http\Requests\CRM\Upload;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Doc
 * @package App\Http\Requests\CRM\Upload
 */
class Doc extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|mimetypes:image/jpg,image/jpeg,image/png,image/gif,image/svg,image/svg+xml,image/webp,application/pdf,application/msword,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.text-template,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/xml,text/xml,text/plain',
            'path' => 'required',
        ];
    }
}
