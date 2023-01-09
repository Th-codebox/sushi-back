<?php


namespace App\Http\Requests\CRM\TechnicalCard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateTechnicalCard
 * @package App\Http\Requests\CRM\TechnicalCard
 */
class UpdateTechnicalCard extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'technicalCardId' => 'numeric|exists:menu_items,id,deleted_at,NULL',
        ];
    }
}
