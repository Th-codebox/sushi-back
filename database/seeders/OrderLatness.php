<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Services\CRM\Order\OrderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderLatness extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ordersService = OrderService::findList(['orderStatus' => OrderStatus::Completed]);

        foreach ($ordersService as $item) {
            try {

                /**
                 * @var OrderService $item
                 */
                $isLatness =  $item->getRepository()->getModel()->dead_line && ($item->getRepository()->getModel()->completed_at->unix() - $item->getRepository()->getModel()->dead_line->unix()) > 0;

                $item->getRepository()->update(['isLateness' => $isLatness]);
            }catch (\Throwable $e) {

            }
        }

    }
}
