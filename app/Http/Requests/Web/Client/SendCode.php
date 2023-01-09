<?php


namespace App\Http\Requests\Web\Client;

use Illuminate\Foundation\Http\FormRequest;

class SendCode extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'phone'    => 'required|phone:RU',
        ];
    }
}
