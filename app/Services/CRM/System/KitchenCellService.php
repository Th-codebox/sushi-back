<?php


namespace App\Services\CRM\System;


use App\Enums\OrderStatus;
use App\Exceptions\CRM\NoFreeKitchenException;
use App\Libraries\Helpers\SettingHelper;
use App\Models\Order\Order;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Order\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class KitchenCellService
{

    public function reserveCellToOrder(Order $order): int
    {

        if ($order->kitchen_cell > 0) return  $order->kitchen_cell;

        if (!$order->filial_id) {
            throw new CRMServiceException("Невозможно зарезервировать ячейку на кухне. Не указан филиал");
        }

        if ($order->order_status->isNot(OrderStatus::Preparing())) {
            throw new CRMServiceException("Невозможно зарезервировать ячейку на кухне. Заказ в статусе '{$order->order_status->description}'");
        }

        $lastCell = $this->gelLastFree($order->filial_id);

        $order->kitchen_cell = $lastCell;
        $order->save();

        return $lastCell;
    }


    /**
     * @param $filialId
     * @return int|string
     * @throws NoFreeKitchenException
     */
    private function gelLastFree($filialId)
    {
        $allCells = $this->mapCells($filialId);

        foreach ($allCells as $cellNumber => $orderId) {
            if ($orderId == 0) {
                return $cellNumber;
            }
        }

        throw new NoFreeKitchenException("В филиале №{$filialId} нет свободных кухонных ячеек. Попробуйте позднее");
    }

    public function getTotalInFilial($filialId)
    {
        return SettingHelper::getSettingValue('kitchenCellCount', $filialId);
    }

    public function mapCells($filialId)
    {
        $cells = $this->getCellsInFilial($filialId);

        $ordersInCells = $this->getOrdersWithReservedCells($filialId);

        $mappedCells = $ordersInCells->reduce(function (array $allCells, Order $order) {
            if ($order->kitchen_cell) {
                $allCells[$order->kitchen_cell] = $order->id;
            }
            return $allCells;
        }, $cells);

        return $mappedCells;
    }

    private function getOrdersWithReservedCells($filialId): Collection
    {
        return Order::where('filial_id', $filialId)
            ->whereIn('order_status', ['confirm', 'preparing', 'assembly'])
            ->where('kitchen_cell', '>', 0)
            ->where('date', '>=', Carbon::today())
            ->get();
    }

    private function getCellsInFilial($filialId): array
    {
        $kitchenCellCount = $this->getTotalInFilial($filialId);
        $cells = [];

        for ($i = 1; $i <= $kitchenCellCount; $i++) {
            $cells[$i] = 0;
        }

        return $cells;
    }
}
