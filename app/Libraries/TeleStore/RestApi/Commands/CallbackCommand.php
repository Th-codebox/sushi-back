<?php


namespace App\Libraries\TeleStore\RestApi\Commands;


use App\Libraries\TeleStore\RestApi\Exceptions\BadCommandResponse;
use App\Libraries\TeleStore\RestApi\Exceptions\BadParamsException;
use Psr\Http\Message\ResponseInterface;

class CallbackCommand implements CommandInterface
{
    protected int $account;
    protected int $number;
    protected bool $prompt = false;

    protected string $commandUrl = "service/accounts/callback";

    public function __construct($account, $number)
    {
        if (empty($account)) {
            throw new BadParamsException("Не указан аккаунт для обратного звонка");
        }

        if (empty($number)) {
            throw new BadParamsException("Не указан номер телефона для обратного звонка");
        }

        $this->account = $account;
        $this->number = $number;
    }


    public function setPrompt(bool $prompt): self
    {
        $this->prompt = $prompt;
        return $this;
    }

    public function getRequest(): array
    {
        return [
            "account" => $this->account,
            "number" => $this->number,
            "prompt" => $this->prompt
        ];
    }

    public function getUri(): string
    {
        return $this->commandUrl;
    }

    public function parseResponse(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 202: return true; //Задание на обратный звонок создано
            case 400: throw new	BadCommandResponse("Переданы неправильные параметры");
            case 503: throw new	BadCommandResponse(
                "Произошла внутренняя ошибка при создании задания на обратный звонок"
            );
            default: throw new	BadCommandResponse("Неизвестный код ответа ТелеСтор REST Api");
        }
    }
}
