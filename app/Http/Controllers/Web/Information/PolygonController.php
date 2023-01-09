<?php


namespace App\Http\Controllers\Web\Information;


use App\Http\Controllers\Controller;

use App\Http\Resources\Web\PolygonResource;
use App\Services\CRM\System\PolygonService;


class PolygonController extends Controller
{
    private PolygonService $polygonService;

    public function __construct(PolygonService $polygonService)
    {
        $this->polygonService = $polygonService;
        $this->resource = PolygonResource::class;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
     */
    public function index()
    {
        $collections = $this->polygonService->findListAsCollectionModel();

        return $this->respondWithCollection($collections);
    }

}
