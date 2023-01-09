<?php


namespace App\Http\Requests\CRM\User;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToBlackList extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason'   => 'string',
            'endBlocking' => 'string',
        ];
    }
}
