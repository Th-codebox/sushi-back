<?php


namespace App\Http\Controllers\CRM;

use App\Exceptions\Http\Controller\RequestClassNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\Search;
use App\Http\Controllers\Traits\Sort;
use App\Http\ResponseTrait;
use App\Services\CRM\CRMBaseService as Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseCRMController
 * @package App\Http\Controllers\CRM
 * @property Service $service
 * @property Request|string $requestCreateClass
 * @property Request|string $requestUpdateClass
 * @property JsonResource|string $resource
 * @property array $data
 */
abstract class BaseCRMController extends Controller
{
    use Search, Sort;

    protected ?Service $service = null;
    protected string $requestCreateClass = '';
    protected string $requestUpdateClass = '';
    protected string $resourceListClass = '';
    protected array $data = [];
    protected string $listKey = 'items';

    /**
     * BaseCRMController constructor.
     * @param Service $service
     * @param string $requestCreateClass
     * @param string $requestUpdateClass
     * @param string $resource
     */
    public function __construct(Service $service, string $requestCreateClass, string $requestUpdateClass, string $resource)
    {
        $this->service = $service;
        $this->requestCreateClass = $requestCreateClass;
        $this->requestUpdateClass = $requestUpdateClass;
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    protected function conditions(): array
    {

        $conditions = [];

        $idList = request()->input('id', []);

        $idList = ((is_array($idList)) ? array_filter($idList, function ($v) {
            return is_numeric($v);
        }) : ((is_numeric($idList)) ? (array)$idList : null));

        if (is_array($idList) && count($idList) > 0) {
            $conditions['id'] = array_values($idList);
        }

        $conditions['filialId'] = request()->input('filialId');

        return $conditions;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Repositories\RepositoryException
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

        if (is_string($page) && $page !== '' && (int)$page > 0) {
            $payLoad['page'] = $page;
        }

        $limit = request()->input('limit', 20);
        $limit = (is_numeric($limit) && (int)$limit >= 0 ? (int)$limit : 20);

        $offset = request()->input('offset', 0);
        $offset = (is_numeric($offset) && (int)$offset >= 0 ? (int)$offset : 0);

        $conditions = static::conditions();

        $result = $this->service::getAllWithPagination(
            [
                'sort'       => $sortParams,
                'search'     => $searchParams,
                'conditions' => $conditions,
                'page'       => $page,
            ],
            $offset,
            $limit
        );

        $meta = [
            'total'   => $result->total(),
            'offset'  => $offset,
            'limit'   => $result->perPage(),
            'payload' => $payLoad,
        ];

        return $this->respondWithCollection($result->items(), $meta);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function show($id)
    {
        $service = $this->service::findOne(['id' => $id]);

        return $this->respondWithItem($service->getRepository()->getModel());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws RequestClassNotFoundException
     */
    public function store(Request $request)
    {

        try {
            $request = $this->requestCreateClass::createFrom($request);
        } catch (\Throwable $e) {
            throw new RequestClassNotFoundException('Ошибка класса валидации');
        }

        $params = $request->all();


        unset($params['id']);

        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails() || !$params) {
            return $this->responseError('Неверные параметры', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }


        $service = $this->service::add($params);

        $this->data = [
            'message' => 'Создана новая запись',
            'id'      => $service->getRepository()->getModel()->id,
        ];

        return $this->responseSuccess($this->data);


    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws RequestClassNotFoundException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function update(Request $request, $id)
    {
        $service = $this->service::findOne(['id' => $id]);

        $request->request->add([
            'id' => $id,
        ]);

        try {
            $request = $this->requestUpdateClass::createFrom($request);
        } catch (\Throwable $e) {
            throw new RequestClassNotFoundException('Ошибка класса валидации');
        }


        $params = $request->all();


        $validation = Validator::make($params, $request->rules(), $request->messages());

        if ($validation->fails()) {
            return $this->responseError('Неверные параметры', Response::HTTP_UNPROCESSABLE_ENTITY, $validation->errors()->toArray());
        }


        $service->edit($request->all());

        $this->data['id'] = $id;
        $this->data['message'] = 'Успещно обновлено!';

        return $this->responseSuccess($this->data);


    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {

            $service = $this->service::findOne(['id' => $id]);


            $service->delete();

            $this->data['message'] = 'Успешно удалено';
            $this->data['total'] = $this->service::count();

            return $this->responseSuccess($this->data);

        } catch (\Exception $e) {
            return $this->responseError('Ошибка удаления: ' . $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


}
