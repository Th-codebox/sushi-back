<?php
/**
 * 6a159d4c-f3aa-4a62-80dc-eaeab49e9d88
 * https://lk.platformaofd.ru/web/noauth/cheque?fn=9999078900005854&fp=1144008787&i=38488
 */

namespace App\Libraries\Atol;

// Классы PSR-совместимого кеша (в данном примере используется Filesystem кеш, может быть любой другой)
use App\Enums\PaymentType;
use App\Libraries\Atol\DTO\DeliveryItem;
use App\Libraries\Atol\DTO\FiscalItem;
use App\Libraries\Atol\Exceptions\AtolOnlineException;
use App\Libraries\Atol\Exceptions\EmptyClientContactsException;
use App\Libraries\System\FilialSettings;
use App\Models\Order\BasketItem;
use App\Models\Store\Filial;
use App\Models\System\Payment\AtolReceipt;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use ItQuasar\AtolOnline\PaymentMethod;
use ItQuasar\AtolOnline\PaymentObject;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

use ItQuasar\AtolOnline\AtolClient;
use ItQuasar\AtolOnline\Client;
use ItQuasar\AtolOnline\Company;
use ItQuasar\AtolOnline\Item;
use ItQuasar\AtolOnline\Payment;
use ItQuasar\AtolOnline\Receipt;
use ItQuasar\AtolOnline\Sell;
use ItQuasar\AtolOnline\Service;
use ItQuasar\AtolOnline\SnoSystem;
use ItQuasar\AtolOnline\Vat;
use ItQuasar\AtolOnline\VatType;

use Illuminate\Contracts\Cache\Repository as CacheRepository;


use App\Models\Order\Order;

class AtolApiClient
{
    private CacheRepository $cache;
    private FilialSettings $filialSettings;

    /** @var AtolClient[] */
    private $clients;

    private bool $testMode = false;

    public function __construct(CacheRepository $cache, FilialSettings $filialSettings)
    {
        $this->cache = $cache;
        $this->filialSettings = $filialSettings;

        $this->testMode = !$this->filialSettings->getDefault('atol.prod_mode');

        // PSR-совместимый логгер (опциональный параметр)
        $this->log = Log::channel('atol');

    }

    private function getFromFilialConfig($setting, $filialId)
    {
        if ($this->testMode) {
            return $this->filialSettings->get("atol.test.{$setting}", $filialId);
        }

        return $this->filialSettings->get("atol.{$setting}", $filialId);
    }

    private function getAtolClient($filialId) : AtolClient
    {
        if (!isset($this->clients[$filialId])) {

            // Логин, пароль и код группы можно найти в "Настройках интергатора", скачиваемых с
            // личного кабинета АТОЛ Онлайн в ноде <access>
            $login = $this->getFromFilialConfig('login', $filialId);
            $password = $this->getFromFilialConfig('password', $filialId);
            $groupCode = $this->getFromFilialConfig('group_code', $filialId);

            $clientClass = AtolClient::class;

            /* TestAtolClient нужен для переопределения тестового хоста апи */
            if ($this->testMode) {
                $clientClass = TestAtolClient::class;
            }


            // Создадим клиент для филиала
            $this->clients[$filialId] = new $clientClass(
                $login,
                $password,
                $groupCode,
                $this->cache,
                $this->log);
        }


        return $this->clients[$filialId];
    }


    /**
     * Создадим атрибуты компании
     */
    private function getFilialCompany(Filial $filial): Company
    {
        $company = ( new Company() )
            ->setEmail('info@sushifox.ru')
            ->setSno($this->getFromFilialConfig('sno', $filial->id))
            ->setInn($this->getFromFilialConfig('inn', $filial->id))
            ->setPaymentAddress($this->getFromFilialConfig('payment_address', $filial->id));
        return $company;
    }

    /**
     * Создадим атрибут налога  НДС
     */
    private function getFilialVat(Filial $filial): Vat
    {
        return ( new Vat() )->setType($this->getFromFilialConfig('vat', $filial->id));
    }


    /**
     * Создаем позиции в чеке
     * #TODO протестировать групировку и подарки, добавить доставку
     */
    private function getItems(Order $order, Vat $vat): Collection
    {
        $sellItems = new Collection();

        /** @var \Illuminate\Database\Eloquent\Collection $basketItems */
        $basketItems = $order->basket->items;

        $basketItems->each(function ($basketItem, $key) use ($sellItems, $vat) {
            $fiscalItem = FiscalItem::fromBasketItem($basketItem, $vat);

            $findItem = false;

            foreach ($sellItems as &$sellItem) {
                /** @var FiscalItem $sellItem */
                if ($sellItem->uuid == $fiscalItem->uuid) {
                    $sellItem->addQuantity();
                    $findItem = true;
                }
            }

            if (!$findItem) {
                $sellItems->push($fiscalItem);
            }

        });


        /* Расчитываем и распределяем скидку */
        if ($order->discount_amount > 0) {
            $priceRate = 1 - $order->discount_amount / ($order->total_price - $order->delivery_price +  $order->discount_amount);

            $sellItems->each(function (FiscalItem $sellItem) use ($priceRate) {
                $sumWithDiscount = $sellItem->getSum() * $priceRate; // round(, 2);
                $sellItem->setSum($sumWithDiscount);
            });
        }


        if ($deliveryItem = $this->getDelivery($order, $vat)) {
            $sellItems->push($deliveryItem);
        }

        #TODO проверка итоговой суммы
        $total_price = $sellItems->reduce(function ($carry, $item) {
            return $carry + $item->getSum() * 100;
        });

        return $sellItems;
    }

    /**
     * Добавляем в чек доставку
     */
    private function getDelivery(Order $order, Vat $vat)
    {
        if ($order->delivery_price) {
            return DeliveryItem::fromOrder($order, $vat);
        }
        return false;
    }

    private function getClient(Order $order): Client
    {
        if (empty($order->client->phone) && empty($order->client->email)) {
            $order->client->email = "sale@sushifox.ru";
        }

        if (!$order->client->phone && !$order->client->email) {
            throw new EmptyClientContactsException("У клиента должно быть заполнено одно из полей phone или email");
        }

        $client = new Client();

        if ($order->client->phone) {
            $client->setPhone("+" . $order->client->phone);
        }

        if ($order->client->email) {
            $client->setEmail((string)$order->client->email);
        }

        /** клиент для тестового режима */
        if ($this->testMode) {
            $client = ( new Client() )->setEmail('kulchickiy.a@yandex.ru');
        }


        return $client;
    }

    /**
     * Создадим оплату
     */
    private function getPayment(Order $order): Payment
    {
        $payment = ( new Payment() )
            ->setSum($order->total_price / 100);

        switch ($order->payment_type->value) {
            case PaymentType::Online:
            case PaymentType::Terminal:
                $payment->setType(Payment::TYPE_ELECTRONIC);
                break;

            case PaymentType::Cash:
                $payment->setType(Payment::TYPE_CASH);
        }

        return $payment;
    }


    public function sendOrder(Order $order)
    {
        // Создадим время заказа
        $timestamp = new \DateTime();
        $timestamp->setTimestamp($order->completed_at->getTimestamp());

        // Создадим атрибут налога НДС
        $vat = $this->getFilialVat($order->filial);

        // Создадим позиции в чеке
        $sellItems = $this->getItems($order, $vat);

// Создадим запрос на продажу
// Параметры запроса соответствуют параметрам запроса, описанным в
// https://raw.githubusercontent.com/0x6368656174/atol-online/master/api/atol-online-v4.6.pdf
        $request = new Sell();
        $request
            ->setExternalId($order->id)
            ->setTimestamp($timestamp);

        // Создадим чек
        $receipt = ( new Receipt() )
            ->setTotal($order->total_price / 100)
            ->setClient($this->getClient($order)) // Установим атрибуты клиента для чека
            ->setCompany($this->getFilialCompany($order->filial)) // Установим атрибуты компании для чека
            ->addPayment($this->getPayment($order)); // Добавим в чек оплату

        foreach ($sellItems as $item) {
            $receipt->addItem($item);
        }

        // Установми чек для запроса
        $request->setReceipt($receipt);


        // Создадим служебный раздел
        $service = ( new Service() )
            ->setCallbackUrl(route('webhooks.atol.status'));

        // Установим служебный раздел для запроса на продажу
        $request->setService($service);


        //$dbAtolReceipt->save();
        $dbAtolReceipt = new AtolReceipt();
        $dbAtolReceipt->order_id = $order->id;
        $dbAtolReceipt->request_object = $request;

        // Отравим запрос
        // $uuid будет содержать UUID документа в системе АТОЛ Онлайн
        $uuid = $this->getAtolClient($order->filial_id)->send($request);

        $dbAtolReceipt->uuid = $uuid;
        $dbAtolReceipt->save();


        /*sleep(10);

        dump($uuid);*/

       /*$report = $this->getAtolClient($order->filial_id)->getReport($uuid);
       dump($report);*/

        /*$dbAtolReceipt->report_object = $report;
        $dbAtolReceipt->status = $report->getStatus();*/



       /* $payload = $report->getPayload();

        $link = sprintf('https://lk.platformaofd.ru/web/noauth/cheque?fn=%s&fp=%s&i=%s',
            $payload->getFnNumber(),
            $payload->getFiscalDocumentAttribute(),
            $payload->getFiscalDocumentNumber()
        );

        echo "<h3>{$link}</h2>";*/

    }


}
