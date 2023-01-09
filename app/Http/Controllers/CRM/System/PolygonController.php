<?php

namespace App\Http\Controllers\CRM\System;

use App\Http\Controllers\CRM\BaseCRMController;
use App\Http\Requests\CRM\Polygon\{UpdatePolygonRequest,StorePolygonRequest};
use App\Http\Resources\CRM\PolygonResource;
use App\Services\CRM\System\PolygonService;


class PolygonController extends BaseCRMController
{
    public function __construct(PolygonService $service)
    {
        parent::__construct($service,StorePolygonRequest::class,UpdatePolygonRequest::class,PolygonResource::class);
    }


}
