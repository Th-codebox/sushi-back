<?php

namespace App\Http\Requests\CRM\Polygon;

use Illuminate\Foundation\Http\FormRequest;

class StorePolygonRequest extends FormRequest
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
            'name'   => 'required|unique:polygons,name',
            'points' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return $fail('Должен быть многомерном массивом с точками');
                    }
                    foreach ($value as $permission) {

                        if (!array_key_exists('0', $permission)) {
                           return $fail('let  обязательное поле в массиве с точками');
                        }

                        if (!array_key_exists('1', $permission)) {
                            return  $fail('lat обязательное поле в массиве с точками');
                        }
                    }

                    $firstArrayPiece = array_pop($value);
                    $lastArrayPiece = array_shift($value);

                    if($firstArrayPiece !== $lastArrayPiece) {
                        return  $fail('Первая и последняя точка должны быть одинаковы');
                    }
                },
            ],
        ];
    }
}
