<?php


namespace App\Http\Requests\CRM\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateCategory
 * @package App\Http\Requests\CRM\Category
 */
class UpdateClient extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'string',
            'phone' => 'phone:RU|unique:clients,phone,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'email' => 'email|unique:clients,email,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
        ];

    }
}
