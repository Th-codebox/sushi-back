<?php

namespace App\Http\Controllers\CRM\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Reports\DateInterval;
use App\Models\Order\BasketItem;
use App\Models\Order\Order;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\ValueObjects\DatePeriod;
use App\Services\CRM\Reports\OrdersReportCreator;
use Carbon\CarbonPeriod;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrdersStatController extends Controller
{
    private OrdersReportCreator $ordersReportCreator;

    public function __construct(OrdersReportCreator $ordersReportCreator)
    {
        $this->ordersReportCreator = $ordersReportCreator;
    }

    public function receiveSummary(DateInterval $request)
    {
        $period = $request->getPeriod();

        $data = [
            'total' => $this->ordersReportCreator->getTotalOrders($period),
            'complete' => $this->ordersReportCreator->getTotalCompleteOrders($period),
            'sourceMap' => $this->ordersReportCreator->getSourceMapByBasketSource($period),
            'phonesMap' => $this->ordersReportCreator->getSourceMapByPhones($period),
            'from' => $period->getStartDate(),
            'to' => $period->getEndDate()
        ];

        //dd($data);

        return view('reports.summary', $data);

    }

    public function couriersOrders(DateInterval $request)
    {
        $period = $request->getPeriod();



        $data = [
            'from' => $period->getStartDate(),
            'to' => $period->getEndDate(),
            'couriersMap' => $this->ordersReportCreator->getCouriersOrdersMap($period),
        ];


        return view('reports.couriers', $data);
    }

    public function orderedProducts(DateInterval $request)
    {
        $period = $request->getPeriod();

        $orders = $this->ordersReportCreator->getCompleteOrders($period);

        $ordersMap = $orders->map(function (Order $order) {

            $products = [];

            foreach ($order->basket->items as $basketItem) {

                /** @var BasketItem $basketItem */

                $find = false;

                foreach ($products as $key => $product) {

                    if ($product['menu_item_id'] == $basketItem->menu_item_id) {
                        $products[$key]['quantity']++;
                        $find = true;
                    }
                }


                if (!$find) {
                    $products[] = [
                        'menu_item_id' => $basketItem->menu_item_id,
                        'name' => $basketItem->menuItem->name,
                        'modification' => $basketItem->modificationMenuItem->modification->name ?? '',
                        'quantity' => 1
                    ];
                }
            }

            return [
                'id' => $order->id,
                'code' => $order->code,
                'filial' => $order->filial->name ?? '-',
                'products' => $products,
                'allItems' => $order->basket->items->count()
            ];
        });


        $data = [
            'from' => $period->getStartDate(),
            'to' => $period->getEndDate(),
            'ordersMap' => $ordersMap,
            'ordersCount' => $ordersMap->count()
        ];


        return view('reports.ordered_products', $data);
    }
}
