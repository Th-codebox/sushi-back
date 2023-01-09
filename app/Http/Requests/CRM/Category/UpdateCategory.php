<?php


namespace App\Http\Requests\CRM\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCategory
 * @package App\Http\Requests\CRM\Category
 */
class UpdateCategory extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'slug'     => 'unique:categories,slug,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id,deleted_at,NULL',
        ];
    }
}
