<?php


namespace App\Http\Requests\CRM\MenuItem;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateModificationMenuItem
 * @package App\Http\Requests\CRM\ModificationMenuItem
 */
class SaveModificationMenuItem extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'modifications' =>
                [
                    'required',
                    'array',
                    function ($attribute, $value, $fail) {

                        if (is_array($value)) {
                            foreach ($value as $modification) {

                                if (!array_key_exists('modificationId', $modification)) {
                                    $fail('modificationId обязательное поле в массиве с модификациями');
                                }

                                if (array_key_exists('priceAdd', $modification) && !is_numeric($modification['priceAdd'])) {
                                    $fail('priceAdd должно быть числовым значением');
                                }

                                if (array_key_exists('priceRate', $modification) && !is_numeric($modification['priceRate'])) {
                                    $fail('priceRate должно быть числовым значением');
                                }
                            }
                        }

                    },
                ],
        ];
    }
}
