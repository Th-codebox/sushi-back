<?php

namespace App\Http\Requests\CRM\Polygon;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePolygonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'unique:polygons,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'area'       => 'array',
        ];
    }
}
