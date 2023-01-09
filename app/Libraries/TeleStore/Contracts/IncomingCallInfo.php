<?php


namespace App\Libraries\TeleStore\Contracts;




use App\Libraries\TeleStore\Enums\CallWebhookEvent;
use App\Libraries\TeleStore\Enums\CallWebhookSource;

interface IncomingCallInfo
{
    /**
     * invite - поступление вызова,
     * answer - ответ абонента,
     * hangup - завершение вызова
     */
    public function getWebhookEvent() : CallWebhookEvent;

    /*
    * источник события
    * number - городской номер,
    * neighbour - "свой" номер,
    * account - внутренний аккаунт
    */
    public function getWebhookSource() : CallWebhookSource;

/*****************************************************************/
    /**
     * номер вызывающего абонента в формате Е.164
     */
    public function getOutPhoneNumber() : string;

    /**
     * номер вызываемого абонента в формате Е.164
     */
    public function getInPhoneNumber() : string;

    /**
     * идентификатор аккаунта, который инициировал вызов
     */
    public function getOutAccount() : string;

    /**
     * идентификатор аккаунта, на который поступил вызов
     */
    public function getInAccount() : string;


    /*
     * идентификатор звонка
     */
    public function getCallId() : string;
/*****************************************************************/


    /**
     * параметры звонка
     */
    public function getCallInfo() : array;

    /**
     * тип вызова
     * incoming - входящий,
     * outgoing - исходящий,
     * missed - пропущенный,
     * internal - внутренний,
     * fax - принятый факс,
     * voicemail - принятое голосовое сообщение
     */
    public function getCallType() : string;
}
