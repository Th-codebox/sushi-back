<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\Search;
use App\Http\Controllers\Traits\Sort;
use App\Libraries\Cache;
use App\Services\Web\CatalogServiceInterface;
use Illuminate\Support\Str;

/**
 * Class BaseWebController
 * @package App\Http\Controllers\Web
 */
class BaseWebController extends Controller
{
    use  Sort, Search;

    protected CatalogServiceInterface $service;

    protected string $itemSearchType = 'id';

    protected array $data = [];

    public function __construct(CatalogServiceInterface $service, string $resource)
    {
        $this->service = $service;
        $this->resource = $resource;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $sortParams = [];
        $searchParams = [];

        if ($this->hasSortRequest()) {
            $sortParams = $this->getSort();
        }

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

    /**
     * @param string $searchParam
     * @return mixed
     */
    public function show(string $searchParam)
    {

        $this->itemSearchType = request()->input('type', 'id');

        if (!in_array($this->itemSearchType, ['slug', 'id'])) {
            $this->itemSearchType = 'id';
        }

        return Cache::getInstance()->remember(get_class($this->service) . '-' . $this->itemSearchType . ':' . $searchParam, 1800, function () use ($searchParam) {
            $itemModel = $this->service->getItemByConditions([$this->itemSearchType => $searchParam, 'noShowHideElement' => true]);
            return $this->respondWithItem($itemModel);
        });
    }

}
