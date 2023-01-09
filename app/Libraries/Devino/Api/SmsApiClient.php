<?php


namespace App\Libraries\Devino\Api;


use App\Libraries\Devino\DevinoSmsException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Hhxsv5\PhpMultiCurl\Curl;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class SmsApiClient
{
    private string $from = "SushiFox";
    private string $apiKey;

    private string $baseUrl = "https://api.devino.online";

    /** @var \GuzzleHttp\Client $httpClient*/
    private Client $httpClient;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        if (empty($this->apiKey)) {
            throw new DevinoSmsException("Set DEVINO_API_KEY in env file");
        }

        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 5.0,
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                "Authorization" => "Key " . $this->apiKey,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ]
        ]);
    }

    /**
     * @param string $toPhone
     * @param string $message
     * @throws \App\Libraries\Devino\DevinoSmsException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($toPhone, $message)
    {
        if (empty($message)) {
            throw new DevinoSmsException("Попытка отправить пустое СМС");
        }

        if (empty($toPhone)) {
            throw new DevinoSmsException("Не указан номер для отправки СМС");
        }

        try {
            $response = $this->httpClient->request(
                "POST",
                "/sms/messages",
                [
                    "json" => [
                        "messages" => [
                            [
                                "from" => $this->from,
                                "to" => $toPhone,
                                "text" => $message
                            ]
                        ]
                    ]
                ]
            );

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new DevinoSmsException('Ошибка расшифровки ответа сервера СМС: '.json_last_error_msg());
            }

            if (!$this->validateResponseData($responseData)) {
                Log::channel('devino_sms')->warning('Devino API SMS Delivery Error', [
                    "toPhone" => $toPhone,
                    "message" => $message,
                    "response" => $responseData
                ]);
            }

        } catch (\Exception $e) {
            Log::channel('devino_sms')->error('Devino API Error', [
                'Exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

        }
    }

    private function validateResponseData($data) : bool
    {
        if (isset($data['result'][0]['code'])
            && $data['result'][0]['code'] == "OK") {
            return true;
        }
        return false;
    }

}
