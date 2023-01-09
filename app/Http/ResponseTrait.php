<?php


namespace App\Http;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Response;

/**
 * Trait ResponseTrait
 * @package App\Http
 * @property string $resource
 */
trait ResponseTrait
{
    /**
     * The current class of resource to respond
     *
     * @var string
     */
    protected string $resource = '';


    protected function responseSuccess($data, $status = 200): JsonResponse
    {

        $data = array_merge(['code' => $status], $data);

        return new JsonResponse($data, $status, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }

    protected function responseError(string $message, $status = 422, ?array $data = null, ?string $exception = null): JsonResponse
    {
        $errorResponse = [
            'code'      => $status,
            'message'   => $message,
            'exception' => $exception,
            'data'      => $data,
        ];
        return new JsonResponse($errorResponse, $status, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return no content for delete requests
     *
     * @return JsonResponse
     */
    protected function respondWithNoContent(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT, ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $result
     * @param array|null $meta
     * @return JsonResponse
     */
    protected function respondWithCollection($result, ?array $meta = null)
    {

        $data['items'] = $this->resource::collection($result);
        $data['meta'] = $meta;

        return $this->responseSuccess($data);
    }

    /**
     * @param Model $item
     * @return mixed
     */
    protected function respondWithItem(Model $item)
    {
        return $this->responseSuccess(['data' => (new $this->resource($item))]);
    }
}
