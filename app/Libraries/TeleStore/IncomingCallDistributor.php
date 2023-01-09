<?php


namespace App\Libraries\TeleStore;


use App\Events\Phone\AnswerCallEvent;
use App\Events\Phone\HangupCallEvent;
use App\Events\Phone\IncomingCallEvent;
use App\Libraries\TeleStore\Contracts\IncomingCallInfo;
use App\Libraries\TeleStore\Enums\CallWebhookEvent;
use App\Libraries\TeleStore\Enums\CallWebhookSource;
use App\Libraries\TeleStore\Exceptions\PhoneEventException;
use App\Models\Order\Order;
use App\Models\Store\Filial;
use App\Models\System\Client;
use App\Models\System\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use http\Exception\InvalidArgumentException;


class IncomingCallDistributor
{
    private string $inPhoneNumber;
    private string $outPhoneNumber;

    private string $filialId;

    private Filial $filial;


    /* При необходимости перенаправить звонок нужно записать массив редиректа */
    private array $redirectData = [];
    private array $log = [];

    /** @var User[] */
    private $users;
    private Client $client;
    private $lastDaysOrderFilialId = 0;

    private IncomingCallInfo $call;
    private AccountRouter $accountRouter;

    public function __construct(AccountRouter $accountRouter)
    {
        $this->accountRouter = $accountRouter;
    }

    public function getRedirectData() : array
    {
        return $this->redirectData;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function process(IncomingCallInfo $call) : void
    {
        $this->call = $call;

        $this->inPhoneNumber = $this->call->getInPhoneNumber();
        $this->outPhoneNumber = str_replace('+', '', $this->call->getOutPhoneNumber());


        $event = $this->call->getWebhookEvent();
        $callType = $this->call->getCallType();
        $source = $this->call->getWebhookSource();

        /* Обрабатываем только входящие */
        if ($callType != 'incoming') return;

        /* Обрабатываем только евенты аккаунтов */
        if ($source != CallWebhookSource::Account) return;



        /* Опредиляем на какой аккаунт поступил звонок */
        $inAccount = $this->call->getInAccount();

        $this->filial = $this->accountRouter->getFilialByPhoneAccount($inAccount);

        /* Получаем сотрудников которых нужно уведомить о звонке */
        $this->users = $this->accountRouter->getUsersByPhoneAccount($inAccount);

        /* Найдем клиента по телефону или создадим пустого */
        $this->client = $this->findClient($this->outPhoneNumber);


        /* Определяем в каком филиале были заказы у клиента в последние дни */
        $this->lastDaysOrderFilialId = $this->getLastDaysOrderFilialId(2);

        switch ($event->value) {
            case CallWebhookEvent::Invite:
                $this->processInviteCall();
                break;

            case CallWebhookEvent::Answer:
                $this->processAnswerCall();
                break;

            case CallWebhookEvent::Hangup:
                $this->processHangupCall();
                break;
        }
    }

    private function redirectCall($newAccounts, $name) : void
    {
        $this->redirectData = [
            "contact" => $name,
            "accounts" => $newAccounts
        ];
    }

    private function addLog($info)
    {
        $this->log[] = $info;
    }


    private function findClient($phone): Client
    {
        return Client::firstOrNew(['phone' => $phone]);
    }

    private function getLastTodayOrderFilialId(Client $client) #TODO
    {
        $lastOrder = $client->orders()->latest()->first();
        if ($lastOrder) {
            return $lastOrder->filial_id;
        }
    }

    private function getLastDaysOrderFilialId($days = 2): int
    {
        if (!$this->client->id) return 0;

        $date = (new Carbon())->setTime(0,0,0)->subDays($days)->toDateTimeString();

        /** @var Order $lastOrder */
        $lastOrder = $this->client->orders()->where('date', '>=', $date)->first();

        if ($lastOrder) {
            return $lastOrder->filial_id;
        }

        return 0;
    }


    /*
    * Проверяем в каком филиале были сегодня заказы и редиректим
    */
    private function processRedirect(): bool
    {
        if (!$this->client->id
            || !$this->lastDaysOrderFilialId
            || $this->lastDaysOrderFilialId == $this->filial->id) {
            /* Редирект не требуется */
            return false;
        }

        $newAccounts = $this->accountRouter->getAccountsByFilial($this->lastDaysOrderFilialId);

        if ($newAccounts) {
            $this->redirectCall($newAccounts, $this->client->name);
            return true;
        }

        return false;

    }


/***************************************************************************/
    /* поступление вызова */
    private function processInviteCall() : void
    {
        if ($this->processRedirect()) {
            //return;
        }

        $this->sendWebsocketEvent(IncomingCallEvent::class);
    }

    /* ответ абонента */
    private function processAnswerCall() : void
    {
        $this->sendWebsocketEvent(AnswerCallEvent::class);
    }

    /* завершение вызова */
    private function processHangupCall() : void
    {
        $this->sendWebsocketEvent(HangupCallEvent::class);
    }
/***************************************************************************/

    private function sendWebsocketEvent($eventClass)
    {
        $client = $this->client;

        $isActiveClient = !empty($client->id);
        $hasOrdersToday = !empty($this->lastDaysOrderFilialId);
        $phone = $client->phone ?? $this->outPhoneNumber;
        $incomingPhone = $this->inPhoneNumber;

        /* Опредиляем на какой аккаунт поступил звонок */
        $inAccount = $this->call->getInAccount();

        event(new $eventClass(
            $this->call->getCallId(),
            $inAccount,
            $phone,
            $incomingPhone,
            $isActiveClient,
            (string)$client->name,
            (int)$client->id,
            $hasOrdersToday,
            $this->users
        ));
    }


}
