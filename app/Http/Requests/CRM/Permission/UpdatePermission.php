<?php


namespace App\Http\Requests\CRM\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdatePermission
 * @package App\Http\Requests\CRM\Permission
 */
class UpdatePermission extends FormRequest
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
