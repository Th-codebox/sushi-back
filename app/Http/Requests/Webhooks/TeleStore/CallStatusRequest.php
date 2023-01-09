<?php

namespace App\Http\Requests\Webhooks\TeleStore;


use App\Libraries\TeleStore\Enums\CallWebhookEvent;
use App\Libraries\TeleStore\Enums\CallWebhookSource;
use App\Libraries\TeleStore\Contracts\IncomingCallInfo;
use Illuminate\Foundation\Http\FormRequest;


class CallStatusRequest extends FormRequest implements IncomingCallInfo
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'event' => 'required|enum_value:' . CallWebhookEvent::class,
            'source' => 'required|enum_value:' . CallWebhookSource::class,
            'vpbx' => "string",
            'call' => 'required|array',
            'call.id' => 'required|string',
            'call.a_number' => 'required',
            'call.b_number' => 'required',
            'call.type' => 'required|string',
            'call.a_account' => 'string|nullable',
            'call.b_account' => 'string|nullable'

        ];
    }


    /**
     * invite - поступление вызова,
     * answer - ответ абонента,
     * hangup - завершение вызова
     */
    public function getWebhookEvent() : CallWebhookEvent
    {
        return  CallWebhookEvent::fromValue($this->input('event'));
    }

    /*
     * источник события
     * number - городской номер,
     * neighbour - "свой" номер,
     * account - внутренний аккаунт
     */
    public function getWebhookSource() : CallWebhookSource
    {
        return  CallWebhookSource::fromValue($this->input('source'));
    }



    /**
     * номер вызывающего абонента в формате Е.164
     */
    public function getOutPhoneNumber() : string
    {
        return $this->input('call.a_number');
    }

    /**
     * номер вызываемого абонента в формате Е.164
     */
    public function getInPhoneNumber() : string
    {
        return $this->input('call.b_number');
    }



    /**
     * идентификатор аккаунта, который инициировал вызов
     */
    public function getOutAccount() : string
    {
        return $this->input('call.a_account', '');
    }

    /**
     * идентификатор аккаунта, на который поступил вызов
     */
    public function getInAccount() : string
    {
        return $this->input('call.b_account', '');
    }




    /*
     * идентификатор звонка
     */
    public function getCallId() : string
    {
        return $this->input('call.id');
    }

    /**
     * параметры звонка
     */
    public function getCallInfo() : array
    {
        return $this->input('call');
    }

    /**
     * тип вызова
     * incoming - входящий,
     * outgoing - исходящий,
     * missed - пропущенный,
     * internal - внутренний,
     * fax - принятый факс,
     * voicemail - принятое голосовое сообщение
     */
    public function getCallType() : string
    {
        return $this->input('call.type');
    }



}

