<?php

namespace App\Models\System;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Enums\TransactionOperationType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionTransitType;
use App\Models\Store\Filial;
use App\Services\CRM\Courier\TransactionService;
use App\Services\CRM\Order\OrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\System\FilialCashBox
 *
 * @property int $id
 * @property int $filial_id
 * @property int $begin_cash
 * @property int $begin_terminal
 * @property int $begin_checks
 * @property int $end_cash
 * @property int $end_terminal
 * @property int $end_checks
 * @property int $proceedTotalToday
 * @property int $totalOrder
 * @property int $totalProceed
 * @property int $cashProceedToday
 * @property int $cashCountToday
 * @property int $onlineProceedToday
 * @property int $onlineCountToday
 * @property int $terminalProceedToday
 * @property int $terminalCountToday
 * @property int $cashTotalToday
 * @property int $checksTotalToday
 * @property int $terminalTotalToday
 * @property int $collectionCashAdd
 * @property int $collectionCashSubtract
 * @property int $collectionTerminalSubtract
 * @property int $collectionChecksSubtract
 * @property Carbon $date
 * @property Carbon $open_at
 * @property Carbon $close_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property array $correctTransactionsList
 * @property array $debtorList
 * @property-read Filial $filial
 * @property-read Transaction $transactions
 *
 */
class FilialCashBox extends Model
{

    public int $proceedTotalToday = 0;
    public int $cashProceedToday = 0;
    public int $cashCountToday = 0;
    public int $onlineProceedToday = 0;
    public int $onlineCountToday = 0;
    public int $terminalProceedToday = 0;
    public int $terminalCountToday = 0;
    public int $terminalTotalToday = 0;
    public int $cashTotalToday = 0;
    public int $checksTotalToday = 0;
    public int $collectionCashAdd = 0;
    public int $collectionCashSubtract = 0;
    public int $collectionTerminalSubtract = 0;
    public int $collectionChecksSubtract = 0;
    public int $totalProceed = 0;
    public int $totalOrder = 0;
    public array $correctTransactionsList = [];
    public array $debtorList = [];
    public int $debtorTotalCash  = 0;
    public int $debtorTotalTerminal  = 0;
    public int $debtorTotalChecks  = 0;

    use SoftDeletes;

    protected $table = 'filial_cash_boxes';


    public string $name;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filial_id',
        'date',
        'open_at',
        'close_at',
        'begin_cash',
        'begin_terminal',
        'begin_checks',
        'end_cash',
        'end_terminal',
        'end_checks',
    ];

    protected $casts = [
        'date'     => 'date', // Example enum cast
        'open_at'  => 'datetime', // Example enum cast
        'close_at' => 'datetime', // Example enum cast
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filial()
    {
        return $this->belongsTo(Filial::class, 'filial_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'filial_cash_box_id', 'id');
    }


}
