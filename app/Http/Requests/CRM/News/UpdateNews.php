<?php


namespace App\Http\Requests\CRM\News;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateNews
 * @package App\Http\Requests\CRM\News
 */
class UpdateNews extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'unique:news,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'slug'     => 'unique:news,slug,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
        ];
    }
}
