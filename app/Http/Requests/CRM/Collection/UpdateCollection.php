<?php


namespace App\Http\Requests\CRM\Collection;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCollection
 * @package App\Http\Requests\CRM\Collection
 */
class UpdateCollection extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'menuItems'  => 'array',
            'types'       => 'array',
            'categoryId' => 'nullable|numeric|exists:categories,id,deleted_at,NULL',
            'slug'       => 'unique:collections,slug,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id,deleted_at,NULL',
        ];
    }
}
