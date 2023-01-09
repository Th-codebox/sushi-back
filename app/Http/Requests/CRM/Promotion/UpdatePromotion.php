<?php


namespace App\Http\Requests\CRM\Promotion;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdatePromotion
 * @package App\Http\Requests\CRM\Promotion
 */
class UpdatePromotion extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'unique:promotions,name,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
            'slug'     => 'unique:promotions,slug,' . (is_numeric(request('id')) ? (int)request('id') : '') . ',id',
        ];
    }
}
