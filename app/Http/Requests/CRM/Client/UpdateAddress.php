<?php


namespace App\Http\Requests\CRM\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddress extends FormRequest
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
            'entry'           => 'nullable|string',
            'apartmentNumber' => 'nullable|string',
            'name'            => 'nullable|string',
            'icoName'         => 'nullable|string',
        ];
    }
}
