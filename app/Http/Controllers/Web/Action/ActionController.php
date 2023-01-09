<?php


namespace App\Http\Controllers\Web\Action;


use App\Enums\RequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Action\SendCooperation;
use App\Http\Requests\Web\Action\SendFeedback;
use App\Http\Requests\Web\Action\SendReview;
use App\Http\Requests\Web\Action\SendVacancy;
use App\Services\CRM\System\RequestService;

class ActionController extends Controller
{

    public function sendVacancy(SendVacancy $request)
    {
        $input = $request->all();

        $input['type'] = RequestType::Vacancy;

        $input['additionalInfo'] = [
            'role'      => $input['role'] ?? null,
            'birthDay'  => $input['birthDay'] ?? null,
            'address'   => $input['address'] ?? null,
            'pcLevel'   => $input['pcLevel'] ?? null,
            'dateBegin' => $input['dateBegin'] ?? null,
            'comment'   => $input['comment'] ?? null,
        ];

        RequestService::add($input);

        return $this->responseSuccess(['status' => true]);
    }

    public function sendFeedBack(SendFeedback $request)
    {
        $input = $request->all();

        $input['type'] = RequestType::Contact;

        RequestService::add($input);

        return $this->responseSuccess(['status' => true]);
    }

    public function sendCooperation(SendCooperation $request)
    {
        $input = $request->all();

        $input['type'] = RequestType::Cooperation;

        $input['additionalInfo'] = [
            'organisationName' => $input['organisationName'] ?? null,
            'inn'              => $input['inn'] ?? null,
            'doc'              => $input['doc'] ?? null,
        ];

        RequestService::add($input);

        return $this->responseSuccess(['status' => true]);
    }

    public function sendReview(SendReview $request)
    {
        $input = $request->all();

        $input['type'] = RequestType::Review;

        $input['additionalInfo'] = [
            'tasteRating'     => $input['tasteRating'] ?? null,
            'tasteComment'    => $input['tasteComment'] ?? null,
            'deliveryRating'  => $input['deliveryRating'] ?? null,
            'deliveryComment' => $input['deliveryComment'] ?? null,
        ];

        RequestService::add($input);

        return $this->responseSuccess(['status' => true]);
    }
}
