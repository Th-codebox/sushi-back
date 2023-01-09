<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Libraries\Payment\UCS\UcsConfig;
use App\Libraries\Payment\UCS\UcsPaymentGateway;
use App\Libraries\System\FilialSettings;
use App\Models\Order\Order;
use App\Models\System\Payment as PaymentModel;
use App\Services\CRM\Order\OrderService;
use App\Services\CRM\System\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class Payment
 * @package App\Jobs
 */
class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $paymentId;


    /**
     * TestJob constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function handle()
    {
      $rand = random_int(0,1);
      if($rand) {
          return 0;
      } else {
           $this->fail('fail polniy');
      }
        return 0;
    }
}
