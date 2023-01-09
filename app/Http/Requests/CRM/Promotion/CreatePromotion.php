<?php


namespace App\Http\Requests\CRM\Promotion;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePromotion
 * @package App\Http\Requests\CRM\Promotion
 */
class CreatePromotion extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required|unique:promotions,name,NULL,id',
            'slug'       => 'unique:promotions,slug,NULL,id',
        ];
    }
}
