<?php

namespace App\Console\Commands;

use App\Models\Store\ClientPromoCode;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Store\ClientPromoCodeService;
use App\Services\CRM\Store\FilialService;
use App\Services\CRM\Store\PromoCodeService;
use App\Services\CRM\System\FilialCashBoxService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class ClearPromoCode extends Command
{

    protected $signature = 'clear_promo_code';

    protected $description = 'clear_promo_code';

    /**
     * ClearApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function handle()
    {

        $promoCodes = ClientPromoCodeService::findList(['deadLine' => true]);


        foreach ($promoCodes as $promoCode) {
            /**
             * @var PromoCodeService $promoCode
             */
            $promoCode->delete();
        }
    }
}
