<?php


namespace App\Http\Requests\CRM\Collection;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateCollection
 * @package App\Http\Requests\CRM\Collection
 */
class CreateCollection extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required',
            'types'       => 'required|array',
            'target'     => 'required',
            'menuItems'   => 'array',
            'categoryId' => 'nullable|numeric|exists:categories,id,deleted_at,NULL',
            'slug'       => 'unique:collections,slug,NULL,id,deleted_at,NULL',
        ];
    }
}
