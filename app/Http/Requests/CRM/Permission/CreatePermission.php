<?php


namespace App\Http\Requests\CRM\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePermission
 * @package App\Http\Requests\CRM\Permission
 */
class CreatePermission extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
        ];
    }
}
