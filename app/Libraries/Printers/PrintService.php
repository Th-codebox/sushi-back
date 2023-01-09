<?php


namespace App\Libraries\Printers;


use App\Enums\CheckType;
use App\Enums\ManufacturerType;
use App\Libraries\DTO\BasketGroupItem;
use App\Libraries\Printers\Checks\BaseCheck;
use App\Libraries\Printers\Checks\ClientMainCheck;
use App\Libraries\Printers\Checks\KitchenCheck;
use App\Libraries\Printers\Checks\OrderSingeMenuItemStickerCheck;
use App\Libraries\Printers\Exceptions\PrinterException;
use App\Libraries\System\FilialSettings;
use App\Models\Domain\Store\SingleMenuItem;
use App\Models\Order\Order;
use KKMClient\Client;
use KKMClient\Exceptions\CheckIsNotPrintable;


class PrintService
{
    protected FilialSettings $filialSettings;

    public function __construct(FilialSettings $filialSettings)
    {
        $this->filialSettings = $filialSettings;
    }

    public function printOrderCheck(Order $order, CheckType $type)
    {
        //dump($order->basket->groupItems($type));
        //dump($order->basket->toArray());

        $products = $order->basket->groupItems($type);

        switch ($type) {

            case CheckType::Main:
                $check = new ClientMainCheck($order, $products);
                break;

            case CheckType::Cold:
            case CheckType::Hot:
                $check = new KitchenCheck($order, $products);
                break;

            default: throw new PrinterException("Для типа '{$type}' не определен шаблон чека");

        }


       if (!empty($_GET['debug-mode'])) {
           dump($type);
           dump($products);

           /** @var BasketGroupItem $product */
           foreach ($products as $product) {
               dump([
                   $product->name,
                   $product->modificationMenuItemNameOn,
                   $product->quantity,
                   $product->menuItem->technicalCard->manufacturer_type->value
               ]);
           }

           exit;
       }



        $printerNumber = $this->getPrinterNumber((string)$type,  $order->filial_id);

        $check->setDeviceNumber($printerNumber);

        $client = $this->getClient($order->filial_id);

        //dump($check->createCommand()->getStrings());

        try {
            return $client->executeCommand($check->createCommand());
        } catch (CheckIsNotPrintable $e) {
            //dump($e);
            // Печать чека не требуется
        }

    }

    public function printOrderMenuItemSticker(Order $order, SingleMenuItem $singleMenuItem, ManufacturerType $type)
    {
        $check = new OrderSingeMenuItemStickerCheck($order, $singleMenuItem);

        $printerNumber = $this->getStickerPrinterNumber((string)$type,  $order->filial_id);

        $check->setDeviceNumber($printerNumber);

        $client = $this->getClient($order->filial_id);

        //dump($check->createCommand()->getStrings());

        try {
            return $client->executeCommand($check->createCommand());
        } catch (CheckIsNotPrintable $e) {
            //dump($e);
            // Печать чека не требуется
        }
    }




    private function getPrinterNumber(string  $type, int $filialId)
    {
        return $this->filialSettings->get('kkm_server.printers.'.$type, $filialId);
    }

    private function getStickerPrinterNumber(string  $type, int $filialId)
    {
        return $this->filialSettings->get('kkm_server.printers.sticker_'.$type, $filialId);
    }

    private function getClient($filialId): Client
    {
        if (empty($filialId)) {
            throw new PrinterException("Не указан филиал для получении клиента kkmServer");
        }

        $host = $this->filialSettings->get('kkm_server.host', $filialId);
        $user = $this->filialSettings->get('kkm_server.user', $filialId);
        $password = $this->filialSettings->get('kkm_server.password', $filialId);

        if (empty($host) || empty($user) || empty($user)) {
            throw new PrinterException("Для филиала '{$filialId}' не заданы доступы к kkmServer");
        }

        return new Client(
            $host . '/Execute',
            [
                'user' => $user,
                'pass' => $password
            ]
        );

    }
}
