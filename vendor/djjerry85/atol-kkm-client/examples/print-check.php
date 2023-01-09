<?php
ini_set('display_errors', 'On');

require './../vendor/autoload.php';

require 'DishStickerCheck.php';
require 'ClientMainCheck.php';

$client = new KKMClient\Client(
    'http://.../Execute',
    [
        'user' => '',
        'pass' => ''
    ]
);


try {
    //$chek = new DishStickerCheck();
    $chek = new ClientMainCheck();
    
    dump($client->executeCommand($chek->createCommand()));
} catch (\KKMClient\Exceptions\CheckIsNotPrintable $e) {
    dump($e);
}



