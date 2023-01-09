<?php


namespace App\Http\Controllers\Web\Client;


use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Client\AddPromoCode;
use App\Http\Requests\Web\Client\EditClient;
use App\Http\Resources\Web\ClientResource;
use App\Libraries\Cache;
use App\Services\CRM\System\ClientService;

class ClientController extends Controller
{
    private ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
        $this->resource = ClientResource::class;
    }

    /**
     * @return array|mixed
     */
    public function getProfile()
    {
        return Cache::getInstance()->remember(get_class($this->clientService) . '-getProfile:' . auth()->user()->id, 1800, function () {

            $profileService = $this->clientService::findOne(['id' => auth()->user()->id]);

            return $this->respondWithItem($profileService->getRepository()->getModel());
        });

    }

    /**
     * @param EditClient $request
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function editProfile(EditClient $request)
    {

        $data = $request->all();

        $profileService = $this->clientService::findOne(['id' => auth()->user()->id]);

        $profileService->edit($data);

        return $this->getProfile();
    }

    /**
     * @param AddPromoCode $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function addPromoCode(AddPromoCode $request)
    {

        $data = $request->all();

        $clientService = $this->clientService::findOne(['id' => auth()->user()->id]);

        $clientService->addPromoCode($data);

        return $this->responseSuccess(['message' => 'Промокод добавлен!']);
    }
}
