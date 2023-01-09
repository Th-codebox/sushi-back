<?php


namespace App\Http\Requests\CRM\Slider;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateSlider
 * @package App\Http\Requests\CRM\Slider
 */
class UpdateSlider extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'unique:sliders,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
        ];
    }
}
