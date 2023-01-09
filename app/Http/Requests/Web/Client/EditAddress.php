<?php


namespace App\Http\Requests\Web\Client;

use Illuminate\Foundation\Http\FormRequest;

class EditAddress extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'city'            => 'nullable',
            'street'          => 'string',
            'house'           => 'string',
            'entry'           => 'nullable',
            'floor'           => 'string|nullable',
            'apartmentNumber' => 'nullable',
            'name'            => 'string|nullable',
            'icoName'         => 'string|nullable',
        ];
    }
}
