<?php


namespace App\Libraries\TeleStore\RestApi;


use App\Libraries\TeleStore\RestApi\Commands\CommandInterface;
use App\Libraries\TeleStore\RestApi\Exceptions\BadCommandResponse;
use App\Libraries\TeleStore\RestApi\Exceptions\BadParamsException;
use GuzzleHttp\Client as HttpClient;

class Client
{
    protected string $baseUrl = "https://www.telestore.ru/rest/";


    protected HttpClient $httpClient;
    protected string $token;

    public function __construct(string $token)
    {
        if (empty($token)) {
            throw new BadParamsException("Не указан token для доступа к ТелеСтор REST Api");
        }

        $this->token = $token;

        $this->httpClient = new HttpClient([
            'base_uri' => $this->baseUrl,
            'timeout' => 5.0,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                "Authorization" => "Bearer " . $this->token,
                //"Accept" => "application/json",
                "Content-Type" => "application/json",
            ]
        ]);
    }

    public function sendCommand(CommandInterface $command)
    {
        $response = $this->httpClient->request(
            "GET",
            $command->getUri(), //"service/accounts/callback",
            [
                "query" => $command->getRequest()
            ]
        );

        $responseCode = $response->getStatusCode();

        if ($responseCode == 403) {
            throw new BadCommandResponse("Доступ запрещен, невалидный токен");
        }

        return $command->parseResponse($response);
    }
}
