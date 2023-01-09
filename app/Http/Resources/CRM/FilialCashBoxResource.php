<?php

namespace App\Http\Resources\CRM;

use App\Models\System\FilialCashBox;

use Illuminate\Http\Resources\Json\JsonResource;

class FilialCashBoxResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function toArray($request)
    {
        $item = $this->resource;

        /**
         * @var FilialCashBox $item
         */


        return [
            'date'                 => $item->date,
            'openAt'               => $item->created_at,
            'closeAt'              => $item->created_at,
            'beginCash'            => $item->begin_cash,
            'beginTerminal'        => $item->begin_terminal,
            'beginChecks'          => $item->begin_checks,
            'endCash'              => $item->end_cash,
            'endTerminal'          => $item->end_terminal,
            'endChecks'            => $item->end_checks

        ];
    }
}
