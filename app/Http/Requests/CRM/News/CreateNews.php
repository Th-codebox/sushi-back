<?php


namespace App\Http\Requests\CRM\News;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateNews
 * @package App\Http\Requests\CRM\News
 */
class CreateNews extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required|unique:news,name',
            'slug'       => 'unique:news,slug',
        ];
    }
}
