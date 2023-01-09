<?php


namespace App\Http\Requests\CRM\Client;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateCategory
 * @package App\Http\Requests\CRM\Category
 */
class CreateClient extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'string',
            'phone' => 'phone:RU|unique:clients,phone',
            'email' => 'email|unique:clients,email',
        ];
    }
}
