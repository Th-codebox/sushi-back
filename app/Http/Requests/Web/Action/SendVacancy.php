<?php


namespace App\Http\Requests\Web\Action;

use Illuminate\Foundation\Http\FormRequest;

class SendVacancy extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'role'      => 'required',
            'name'      => 'required',
            'phone'     => 'required|phone:RU',
            'email'     => 'required',
            'birthDay'  => 'required',
            'address'   => 'required',
            'pcLevel'   => 'required',
            'dateBegin' => 'required',
            'comment'   => 'nullable',
        ];
    }
}
