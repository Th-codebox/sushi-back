<?php

namespace App\Http\Controllers\CRM\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Reports\DateInterval;
use App\Models\Order\BasketItem;
use App\Models\Order\Order;
use App\Models\Store\Filial;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\ValueObjects\DatePeriod;
use App\Services\CRM\Reports\OrdersReportCreator;
use App\Services\CRM\System\KitchenCellService;
use Carbon\CarbonPeriod;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class KitchenStatController extends Controller
{

    public function cellReservation(Request $request, KitchenCellService $kitchenCellService)
    {
        $filials = Filial::all();

        $cells = [];

        foreach ($filials as $filial) {
            /** @var Filial $filial */
            $cells[] = [
                'id' => $filial->id,
                'name' => $filial->name,
                'map' => $kitchenCellService->mapCells($filial->id)
            ];

            dump($kitchenCellService->mapCells($filial->id));
        }

        $data = [
            'cells' => $cells
        ];
        exit();

        //dd($data);

        return view('reports.summary', $data);

    }

}
