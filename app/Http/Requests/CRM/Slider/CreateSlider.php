<?php


namespace App\Http\Requests\CRM\Slider;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateSlider
 * @package App\Http\Requests\CRM\Slider
 */
class CreateSlider extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required|unique:sliders,name',
        ];
    }
}
