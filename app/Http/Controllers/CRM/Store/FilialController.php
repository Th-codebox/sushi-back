<?php

namespace App\Http\Controllers\CRM\Store;

use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\Filial\{UpdateFilialRequest,StoreFilialRequest};
use App\Http\Resources\CRM\FilialResource;
use App\Services\CRM\Store\FilialService;


class FilialController extends BaseCRMController
{
    public function __construct(FilialService $service)
    {
        parent::__construct($service,StoreFilialRequest::class,UpdateFilialRequest::class,FilialResource::class);
    }


    public function getBalance() {

    }


}
