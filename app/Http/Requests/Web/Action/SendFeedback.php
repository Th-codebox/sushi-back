<?php


namespace App\Http\Requests\Web\Action;

use Illuminate\Foundation\Http\FormRequest;

class SendFeedback extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name'  => 'required',
            'phone' => 'required|phone:RU',
            'text'  => 'string',
        ];
    }
}
