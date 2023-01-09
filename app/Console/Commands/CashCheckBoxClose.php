<?php

namespace App\Console\Commands;

use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Store\FilialService;
use App\Services\CRM\System\FilialCashBoxService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class CashCheckBoxClose extends Command
{

    protected $signature = 'cash_check_box_close';

    protected $description = 'cash_check_box_close';

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

        $existOpenCashBoxes = FilialCashBoxService::findList(['closeAt' => null]);

        foreach ($existOpenCashBoxes as $existOpenCashBox) {
            /**
             * @var FilialCashBoxService $existOpenCashBox
             */
            if ($existOpenCashBox !== null) {

                $existOpenCashBox->getAllInfo(Carbon::now()->toDateString());

                $existOpenCashBox->edit([
                    'closeAt'     => Carbon::now(),
                    'endCash'     => $existOpenCashBox->getRepository()->getModel()->cashTotalToday,
                    'endTerminal' => $existOpenCashBox->getRepository()->getModel()->terminalTotalToday,
                    'endChecks'   => $existOpenCashBox->getRepository()->getModel()->checksTotalToday,
                ]);

                FilialCashBoxService::add([
                    'filialId'      => $existOpenCashBox->getRepository()->getModel()->filial_id,
                    'openAt'        => Carbon::now(),
                    'date'          => $existOpenCashBox->getRepository()->getModel()->date->addDay(),
                    'beginCash'     => $existOpenCashBox->getRepository()->getModel()->cashTotalToday,
                    'beginTerminal' => $existOpenCashBox->getRepository()->getModel()->terminalTotalToday,
                    'beginChecks'   => $existOpenCashBox->getRepository()->getModel()->checksTotalToday,
                ]);
            }
        }


    }
}
