<?php


namespace App\Libraries;


use App\Libraries\Notification\NotificationException;
use App\Libraries\Notification\SendersInterface;
use Hhxsv5\PhpMultiCurl\Curl;

/**
 * Class SmsSender
 * @package App\Library
 */
class SmsSender implements SendersInterface
{
    private string $url;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->username = config('app.sms_username');
        $this->password = config('app.sms_password');
        $this->url = 'https://smsc.ru/sys/send.php';
    }

    /**
     * @param array $to
     * @param string $text
     * @return \Hhxsv5\PhpMultiCurl\Response|mixed
     * @throws NotificationException
     */
    public function send(array $to,string $text)
    {

        $to = is_array($to) ? $to : [$to];

        $data = [
            'phones' => implode(',', $to),
            'mes'    => $text,
            'login'  => $this->username,
            'psw'    => $this->password,
            'id'    => 0,
        ];

        return $this->sendMessage($data);
    }

    /**
     * @param $data
     * @return \Hhxsv5\PhpMultiCurl\Response
     * @throws NotificationException
     */
    public function sendMessage($data)
    {
        try {
            $headers = $this->headersGenerate($data);
            $curl = new Curl;
            $curl->makeGet($this->url, $data, $headers);
            return $curl->exec();
        } catch (\Throwable $e) {
            throw new NotificationException($e->getMessage());
        }


    }

    /**
     * @param array $data
     * @return array
     */
    private function headersGenerate($data = [])
    {
        ksort($data);
        reset($data);
        $time = microtime() . rand(0, 10000);

        return [
            'username: ' . $this->username,
            'ts: ' . $time,
            'sig: ' . md5(implode('', $data) . $time . $this->password),
        ];
    }
}
