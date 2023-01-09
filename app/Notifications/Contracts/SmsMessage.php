<?php

namespace App\Notifications\Contracts;


class SmsMessage
{
    /** The sms message content. */
    protected string $content;


    public function __construct(string $content = '')
    {
        $this->content = $content;
    }


    public function content($content) : self
    {
        $this->content = $content;
        return $this;
    }

    public function render() : string
    {
        return $this->content;
    }

}
