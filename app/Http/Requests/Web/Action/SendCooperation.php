<?php


namespace App\Http\Requests\Web\Action;

use Illuminate\Foundation\Http\FormRequest;

class SendCooperation extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'organisationName' => 'required',
            'phone'            => 'required',
            'inn'              => 'required',
            'name'             => 'required',
            'text'             => 'required',
            'doc'             => 'string',
        ];
    }
}
