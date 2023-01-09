<?php

namespace App\Http\Controllers\CRM\System;

use App\Enums\CheckType;
use App\Enums\ManufacturerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Printing\PrintOrderCheck;
use App\Http\Requests\CRM\Printing\PrintSticker;
use App\Libraries\Printers\PrintService;
use App\Models\Order\Order;
use App\Services\CRM\System\KitchenCellService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrintingController extends Controller
{
    private PrintService $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    public function printCheck(PrintOrderCheck $request): JsonResponse
    {
        $order = Order::findOrFail($request->orderId);
        $type = CheckType::fromValue($request->type);

        $printServerResponse = $this->printService->printOrderCheck($order, $type);

        return $this->responseSuccess([
            'status'=> true,
            'data' => (array)$printServerResponse
        ]);
    }

    public function printSticker(PrintSticker $request, KitchenCellService $kitchenCellService)
    {
        /** @var Order $order */
        $order = Order::findOrFail($request->orderId);

        if (!$order->kitchen_cell) {
            $kitchenCellService->reserveCellToOrder($order);
        }

        $singleMenuItem = $order
            ->getAllSingleMenuItems()
            ->getItem($request->basketItemId, $request->menuItemId);

        $type = ManufacturerType::fromValue($request->type);

        $printServerResponse = $this->printService->printOrderMenuItemSticker($order, $singleMenuItem, $type);

        return $this->responseSuccess([
            'status'=> true,
            'data' => (array)$printServerResponse
        ]);
    }
}
