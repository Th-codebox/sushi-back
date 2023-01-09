<?php


namespace App\Libraries\TeleStore\RestApi\Commands;


use App\Libraries\TeleStore\RestApi\Exceptions\BadCommandResponse;
use Psr\Http\Message\ResponseInterface;

interface CommandInterface
{
    public function getRequest(): array;

    public function getUri(): string;

    /**
     * Разбор ответа апи сервера
     * @param ResponseInterface $response
     * @return mixed
     * @throws  BadCommandResponse
     */
    public function parseResponse(ResponseInterface $response);
}
