<?php


namespace App\Http\Requests\CRM\Client;

use Illuminate\Foundation\Http\FormRequest;

class AddAddress extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'city'            => 'nullable',
            'street'          => 'required',
            'house'           => 'required',
            'entry'           => 'nullable',
            'apartmentNumber' => 'nullable',
            'name'            => 'nullable',
            'icoName'            => 'nullable',
        ];
    }
}
