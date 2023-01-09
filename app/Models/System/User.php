<?php

namespace App\Models\System;

use App\Enums\OrderStatus;
use App\Enums\TransactionPaymentType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionOperationType;
use App\Models\Order\Order;
use App\Models\System\Transaction;
use App\Models\Store\Filial;
use App\Services\CRM\Store\FilialService;
use App\Services\CRM\System\WorkScheduleService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models\System
 * @property int $id
 * @property string $fullName
 * @property string $first_name
 * @property string $last_name
 * @property string $surname
 * @property string $phone
 * @property string $image
 * @property string $password
 * @property string $email
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $last_visit_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Role $role
 * @property-read Collection $filials
 * @property-read UserDoc[] $docs
 * @property-read Transaction[] $transactions
 * @property-read Transaction[] $operatorTransactions
 * @property-read UserDevice[] $devices
 * @property-read Order[] $orders
 * @property-read WorkSchedule[] $workSchedules
 * @property Filial $actualFilial
 *
 * @method public calcBalance()
 *
 * @property int $cashBalance
 * @property int $terminalBalance
 * @property int $cashOperations
 * @property int $terminalOperations
 * @property int $quantityChecks
 * @property int $todayDeliveries
 * @property int $todayLateness
 * @property int $allDeliveries
 * @property int $allLateness
 * @property int $workedShifts
 * @property int $allDays
 * @mixin \Eloquent
 */
class User extends AuthUser implements JWTSubject
{
    use SoftDeletes, HasFactory, Notifiable;

    public string $fullName;
    public int $cashBalance = 0;
    public int $terminalBalance = 0;
    public int $cashOperations = 0;
    public int $terminalOperations = 0;
    public int $quantityChecks = 0;

    public int $todayDeliveries = 0;
    public int $todayLateness = 0;
    public int $allDeliveries = 0;
    public int $allLateness = 0;
    public int $workedShifts = 0;
    public int $allDays = 0;

    public ?Filial $actualFilial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'surname',
        'email',
        'phone',
        'image',
        'password',
        'last_visit_at',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];


    protected $appends = [
        'name',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scoperFullName()
    {
        $this->fullName = trim($this->last_name . ' ' . $this->first_name);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filials()
    {
        return $this->belongsToMany(
            Filial::class,
            'user_to_filial',
            'user_id',
            'filial_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function docs()
    {
        return $this->hasMany(
            UserDoc::class,
            'user_id',
            'id'
        );
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'courier_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'user_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getCurrentWorkSchedule()
    {
        return $this->hasOne(WorkSchedule::class, 'user_id', 'id')->whereDate('date', Carbon::now()->toDateString());
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id', 'id')->where('archived_at', '=', null)->whereIn('status', [TransactionStatus::Completed])->with('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorTransactions()
    {
        return $this->hasMany(Transaction::class, 'operator_id', 'id')->where('archived_at', '=', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allTransaction()
    {
        return $this->hasMany(Transaction::class, 'sender_id', 'id');
    }


    public function calcBalance()
    {
        $subtract = [
            TransactionOperationType::Send,
        ];

        $add = [
            TransactionOperationType::TopUpOrder,
            TransactionOperationType::Receive,
        ];

        $this->transactions->map(function (Transaction $item) use ($subtract, $add) {

            if ($item->status->is(TransactionStatus::Completed)) {

                if ((string)$item->payment_type === TransactionPaymentType::Cash) {


                    if (in_array((string)$item->operation_type, $subtract, true)) {
                        $this->cashBalance -= $item->price;
                    } elseif (in_array((string)$item->operation_type, $add, true)) {
                        $this->cashBalance += $item->price;
                        $this->cashOperations++;
                    }

                } else {


                    if (in_array((string)$item->operation_type, $subtract, true)) {
                        $this->terminalBalance -= $item->price;
                        $this->quantityChecks -= $item->quantity_checks;

                    } elseif (in_array((string)$item->operation_type, $add, true)) {
                        $this->terminalBalance += $item->price;
                        $this->quantityChecks += $item->quantity_checks;

                    }
                }
            }
        });

    }


    public function information(): void
    {

        $this->allDays = Carbon::now()->diffInDays($this->created_at);

        $nowDate = Carbon::now();

        $this->orders->map(function (Order $order) use ($nowDate) {

            if((string)$order->order_status === OrderStatus::Completed) {

                if ($nowDate->toDateString() === $order->date->toDateString()) {

                    $this->todayDeliveries++;

                    if ($order->hasLateness()) {
                        $this->todayLateness++;
                    }
                }

                if ($order->hasLateness()) {
                    $this->allLateness++;
                }
            }
        });

        $this->workedShifts = count(WorkScheduleService::findList(['userId' =>  $this->id,'!date' => Carbon::now()->toDateString()]));

        $this->allDeliveries = $this->orders->count();
    }


    /**
     * @throws \App\Repositories\RepositoryException
     * @throws \App\Services\CRM\CRMServiceException
     */
    public function searchActualFilial(): void
    {
        /**
         * @var WorkSchedule $todayWorkSchedule
         * @var WorkSchedule $lastWorkSchedule
         */

        try {
            $todayWorkSchedule = WorkScheduleService::findOne(['userId' => $this->id, 'date' => Carbon::now()->toDateString()])->getRepository()->getModel();
            $filialId = $todayWorkSchedule->workSpace->filial_id;

        } catch (\Throwable $e) {
            if ($this->filials->count() > 1) {

                try {
                    $lastWorkSchedule = WorkScheduleService::findOne(['user' => $this->id, 'sort' => 'new'])->getRepository()->getModel();
                    $filialId = $lastWorkSchedule->workSpace->filial_id;
                } catch (\Throwable $e) {
                    $filialId = 1;
                }

            } else {
                $this->filials->map(function (Filial $filial) use (&$filialId) {
                    $filialId = $filial->id;
                });
            }
        }

        try {
            $this->actualFilial = FilialService::findOne(['id' => $filialId])->getRepository()->getModel();

        } catch (\Throwable $e) {
            $this->actualFilial = FilialService::findOne()->getRepository()->getModel();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(
            UserDevice::class,
            'user_id',
            'id'
        );
    }


    /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        $tokens = [];

        if ($this->devices) {
            foreach ($this->devices as $device) {
                if ($device->push_token) {
                    $tokens[] = $device->push_token;
                }
            }
        }

        return $tokens;
    }

    public function getNameAttribute()
    {
        return implode(" ", [$this->first_name, $this->last_name]);
    }
}
