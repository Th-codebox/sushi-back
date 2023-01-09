<?php


namespace App\Http\Requests\CRM\Modification;

use App\Enums\{ModificationAction, ModificationType};

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateModification
 * @package App\Http\Requests\CRM\Modification
 */
class CreateModification extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name'            => 'required|string',
            'priceRate'       => 'required|string|unique:modifications,name',
            'priceAdd'        => 'required|string|unique:modifications,name',
            'technicalCardId' => 'nullable|numeric|exists:technical_cards,id',
            'action'          => 'enum_value:' . ModificationAction::class,
            'type'            => 'enum_value:' . ModificationType::class,
        ];
    }
}
