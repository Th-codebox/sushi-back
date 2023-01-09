<?php


namespace App\Http\Requests\Web\Action;

use Illuminate\Foundation\Http\FormRequest;

class SendReview extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'tasteRating'     => 'required',
            'tasteComment'    => 'required',
            'deliveryRating'  => 'required',
            'deliveryComment' => 'required',
        ];
    }
}
