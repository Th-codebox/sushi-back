<?php


namespace App\Http\Requests\CRM\TechnicalCard;

use App\Enums\CookingType;
use App\Enums\DishType;
use App\Enums\ManufacturerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateTechnicalCard
 * @package App\Http\Requests\CRM\TechnicalCard
 */
class CreateTechnicalCard extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name'             => ['required'],
            'cookingType'      => 'nullable|enum_value:' . CookingType::class,
            'dishType'         => 'nullable|enum_value:' . DishType::class,
            'manufacturerType' => 'nullable|enum_value:' . ManufacturerType::class,
        ];
    }
}
