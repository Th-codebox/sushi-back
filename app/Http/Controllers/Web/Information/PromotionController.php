<?php

namespace App\Http\Controllers\Web\Information;


use App\Http\Controllers\Web\BaseWebController;
use App\Http\Resources\Web\PromotionResource;

use App\Libraries\Cache;
use App\Services\Web\Information\PromotionService;

/**
 * Class PromotionController
 * @package App\Http\Controllers\Web\Catalog
 */
class PromotionController extends BaseWebController
{

    /**
     * PromotionController constructor.
     * @param PromotionService $service
     */
    public function __construct(PromotionService $service)
    {
        parent::__construct($service, PromotionResource::class);
    }

    public function index()
    {

        $sortParams = [
            'sortField' => 'sort_order',
            'sortMethod' => 'desc',
        ];

        $searchParams = [];


        if ($this->hasSearchRequest()) {
            $searchParams = $this->getSearchParams();
        }

        $payLoad = $sortParams + $searchParams;

        $page = request()->input('page', '1');

        $withoutRelation = request()->input('withoutRelation', false);

        if (is_string($page) && $page !== '' && (int)$page > 0) {
            $payLoad['page'] = $page;
        }

        $limit = request()->input('limit', 20);
        $limit = (is_numeric($limit) && (int)$limit >= 0 ? (int)$limit : 20);

        $offset = request()->input('offset', 0);
        $offset = (is_numeric($offset) && (int)$offset >= 0 ? (int)$offset : 0);

        $conditions = request()->all();

        $conditions['noShowHideElement'] = true;

        $params = [
            'sort'            => $sortParams,
            'search'          => $searchParams,
            'conditions'      => $conditions,
            'page'            => $page,
            'withoutRelation' => $withoutRelation,
        ];

        return Cache::getInstance()->remember(get_class($this->service) . http_build_query($conditions), 1800, function () use ($params, $offset, $limit) {
            $result = $this->service->getItemsWithPagination($params, $offset, $limit);

            $meta = [
                'total'   => $result->total(),
                'offset'  => $offset,
                'limit'   => $result->perPage(),
                'payload' => $params,
            ];

            return $this->respondWithCollection($result->items(), $meta);
        });

    }

}
