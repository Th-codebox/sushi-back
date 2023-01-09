<?php


namespace App\Http\Requests\Courier\Information;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Get
 * @package App\Http\Requests\Courier\Information
 */
class SaveCord extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lat'  => 'required',
            'let'  => 'required',
        ];
    }
}
