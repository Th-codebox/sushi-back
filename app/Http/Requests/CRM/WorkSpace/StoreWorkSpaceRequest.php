<?php

namespace App\Http\Requests\CRM\WorkSpace;

use App\Enums\ShiftTime;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkSpaceRequest extends FormRequest
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
            'name'     => 'required|string',
            'filialId' => 'required|exists:filials,id',
            'roleId'   => 'required|exists:roles,id',
        ];
    }
}
