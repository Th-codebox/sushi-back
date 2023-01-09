<?php

namespace App\Http\Requests\CRM\WorkSpace;

use App\Enums\ShiftTime;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkSpaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'string',
            'filialId' => 'numeric|exists:filials,id',
            'roleId'   => 'numeric|exists:roles,id',
        ];
    }
}
