<?php

namespace App\Console\Commands\Payments;

use App\Models\Store\ClientPromoCode;
use App\Models\System\Payment;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Store\ClientPromoCodeService;
use App\Services\CRM\Store\FilialService;
use App\Services\CRM\Store\PromoCodeService;
use App\Services\CRM\System\FilialCashBoxService;
use App\Services\Payment\PaymentStatusService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class CheckPaymentsStatus extends Command
{

    protected $signature = 'payments:check';

    protected $description = 'Check payments status';

    private PaymentStatusService $paymentStatusService;

    /**
     * ClearApp constructor.
     */
    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->paymentStatusService = $paymentStatusService;
        parent::__construct();
    }


    public function handle()
    {
        $this->paymentStatusService->updateAllWaitingPaymentOrders();
    }
}
