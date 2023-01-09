<?php


namespace App\Http\Requests\CRM\Upload;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Image
 * @package App\Http\Requests\CRM\Upload
 */
class Image extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'required|mimetypes:image/jpg,image/jpeg,image/png,image/gif,image/svg,image/svg+xml,image/webp',
            'path'  => 'required',
        ];
    }
}
