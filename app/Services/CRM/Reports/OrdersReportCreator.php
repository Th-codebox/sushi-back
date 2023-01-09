<?php


namespace App\Services\CRM\Reports;


use App\Enums\BasketSource;
use App\Enums\OrderStatus;
use App\Models\Order\Order;
use App\Models\System\Role;
use App\Models\System\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Connection as DB;

class OrdersReportCreator
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function getTotalOrders(CarbonPeriod $period, int $filialId = 0): int
    {
        return Order::whereBetween('date', [
            $period->getStartDate(),
            $period->getEndDate()
        ])->count();
    }

    public function getTotalCompleteOrders(CarbonPeriod $period, int $filialId = 0): int
    {
        return Order::whereBetween('date', [
            $period->getStartDate(),
            $period->getEndDate()
        ])->where('order_status', OrderStatus::Completed)->count();
    }

    public function getSourceMapByBasketSource(CarbonPeriod $period, int $filialId = 0)
    {
        return $this->db->table('orders')
            ->join('baskets', 'orders.basket_id', '=', 'baskets.id')
            ->select("baskets.basket_source")
            ->selectRaw("COUNT(orders.id) as count")
            ->whereBetween('orders.date', [
                $period->getStartDate(),
                $period->getEndDate()
            ])
            ->where('orders.order_status', OrderStatus::Completed)
            ->groupBy("baskets.basket_source")
            ->get()
            ->sortByDesc('count');;
    }

    public function getSourceMapByPhones(CarbonPeriod $period, int $filialId = 0)
    {
        return $this->db->table('orders')
            ->join('baskets', 'orders.basket_id', '=', 'baskets.id')
            ->select("baskets.call_phone")
            ->selectRaw("COUNT(orders.id) as count")
            ->whereBetween('orders.date', [
                $period->getStartDate(),
                $period->getEndDate()
            ])
            ->where('orders.order_status', OrderStatus::Completed)
            ->where('baskets.basket_source', BasketSource::Crm)
            ->groupBy("baskets.call_phone")
            ->get()
            ->sortByDesc('count');
    }

    public function getCouriersOrdersMap(CarbonPeriod $period, int $filialId = 0)
    {
        $couriers = User::where(['role_id' => Role::Courier])->whereNotIn('id', [2, 10, 11, 12])->get();


        $couriers->each(function ($user, $index) use ($period) {
            $completeOrdersCount = Order::whereBetween('date', [
                $period->getStartDate(),
                $period->getEndDate()
            ])
                ->where('courier_id', $user->id)
                ->where('order_status', OrderStatus::Completed)
                ->count();

            $user->completeOrdersCount = $completeOrdersCount;
        });

        return $couriers;
    }

    /**
     * @param CarbonPeriod $period
     * @param int $filialId
     * @return Order[]
     */
    public function getCompleteOrders(CarbonPeriod $period, int $filialId = 0)
    {
        return  Order::whereBetween('date', [
            $period->getStartDate(),
            $period->getEndDate()
            ])
            ->with(
                'filial',
                'basket',
                'basket.items',
                //'basket.clientPromoCode.promoCode',
                'basket.items.menuItem',
                'basket.items.modificationMenuItem.modification',
                //'basket.items.menuItem.categories',
                //'basket.items.menuItem.modifications',
                //'basket.items.menuItem.technicalCard'
            )
            ->where('order_status', OrderStatus::Completed)
            ->get();
    }
}
