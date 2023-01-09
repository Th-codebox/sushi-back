<?php


namespace App\Libraries\Atol;


use ItQuasar\AtolOnline\AtolClient;
use ItQuasar\AtolOnline\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\SimpleCache\CacheInterface;

class TestAtolClient extends AtolClient
{

    public function __construct(string $login, string $password, string $groupCode, CacheInterface $cache, LoggerInterface $logger = null)
    {
        $this->setHost('https://testonline.atol.ru');
        parent::__construct($login, $password, $groupCode, $cache, $logger);
    }
}
