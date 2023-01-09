<?php

namespace App\Http\Controllers\CRM\System;

use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\WorkSpace\{UpdateWorkSpaceRequest,StoreWorkSpaceRequest};
use App\Http\Resources\CRM\WorkSpaceResource;
use App\Services\CRM\System\WorkSpaceService;


class WorkSpaceController extends BaseCRMController
{
    public function __construct(WorkSpaceService $service)
    {
        parent::__construct($service,StoreWorkSpaceRequest::class,UpdateWorkSpaceRequest::class,WorkSpaceResource::class);
    }


}
