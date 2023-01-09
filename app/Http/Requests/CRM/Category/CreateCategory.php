<?php


namespace App\Http\Requests\CRM\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateCategory
 * @package App\Http\Requests\CRM\Category
 */
class CreateCategory extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required',
            'slug'       => 'unique:categories,slug,NULL,id,deleted_at,NULL',
        ];
    }
}
