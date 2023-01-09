<?php


namespace App\Libraries\Notification;


interface  SendersInterface
{
    /**
     * @param array $to
     * @param string $content
     * @return mixed
     */
  public function send(array $to, string $content);
}
