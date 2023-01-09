<?php


namespace App\Libraries\Payment\UCS;


use App\Libraries\System\FilialSettings;

class UcsConfig
{
    private FilialSettings $filialSettings;

    private string $configPrefix = 'ucs_payment_gateway';
    private bool $testMode = false;


    protected array $baseOptions = [
        'trace' => 1,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS
    ];


    public function __construct(FilialSettings $filialSettings)
    {
        $this->filialSettings = $filialSettings;
        $this->testMode = !$this->filialSettings->getDefault("ucs_payment_gateway.prod_mode");
    }

    private function getConfigKey($key): string
    {
        if ($this->testMode) {
            return $this->configPrefix . '.test.' . $key;
        }

        return $this->configPrefix . '.' . $key;
    }


    public function getOrderClientOptions(): array
    {
        $options = $this->baseOptions;

        if ($this->testMode) {
            $options['location'] = "https://tws.egopay.ru/order/v2/";
        } else {
            $options['location'] = "https://ws.egopay.ru/order/v2/";
        }

        $options['login'] = $this->filialSettings->getDefault($this->getConfigKey('login'));
        $options['password'] = $this->filialSettings->getDefault($this->getConfigKey("password"));

        $options['uri'] = config('app.url');

        return $options;
    }

    public function getStatusClientOptions(): array
    {
        $options = $this->baseOptions;

        if ($this->testMode) {
            $options['location'] = "https://tws.egopay.ru/status/v2/";
        } else {
            $options['location'] = "https://ws.egopay.ru/status/v2/";
        }

        $options['login'] = $this->filialSettings->getDefault($this->getConfigKey('login'));
        $options['password'] = $this->filialSettings->getDefault($this->getConfigKey("password"));

        $options['uri'] = config('app.url');

        return $options;
    }

    public function getShopId(int $filialId)
    {
        return $this->filialSettings->get($this->getConfigKey('shop_id'), $filialId);
    }





}
