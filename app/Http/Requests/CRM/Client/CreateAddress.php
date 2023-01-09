<?php


namespace App\Http\Requests\CRM\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddress extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'clientId'        => 'required|exists:clients,id',
            'city'            => 'required',
            'street'          => 'required',
            'house'           => 'required',
            'entry'           => 'nullable',
            'apartmentNumber' => 'nullable',
            'name'            => 'nullable',
            'icoName'         => 'nullable',
        ];
    }
}
