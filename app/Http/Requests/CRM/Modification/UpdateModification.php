<?php


namespace App\Http\Requests\CRM\Modification;

use App\Enums\{ModificationAction,ModificationType};

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateModification
 * @package App\Http\Requests\CRM\Modification
 */
class UpdateModification extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'action' => 'enum_value:' . ModificationAction::class,
            'type' => 'enum_value:' . ModificationType::class,
        ];
    }
}
