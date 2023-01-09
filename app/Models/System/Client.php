<?php

namespace App\Models\System;

use App\Enums\PromoCodeAction;
use App\Models\Order\Order;
use App\Models\Store\ClientPromoCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models\System
 * @property int $id
 * @property string $name
 * @property string $referral_promo_code
 * @property string $phone
 * @property string $code
 * @property string $email
 * @property bool $status
 * @property bool $confirm_phone
 * @property ?Carbon $birthday
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $code_last_send_at
 * @property string|null $utm
 * @property string|null $remember_token
 * @property Carbon|null $last_visit_at
 * @property string|null $deleted_at
 * @property int $countFriendPercent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read ClientAddress[] $clientAddresses
 * @property-read BlackListClient $hasActiveBlock
 * @property-read BlackListClient[] $blackList
 * @property-read ClientPromoCode[] $clientPromoCodes
 * @property-read ClientPromoCode[] $actualClientPromoCodes
 * @mixin \Eloquent
 */
class Client extends AuthUser implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    public $groupClientPromoCodes;
    public int $countFriendPercent = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referral_promo_code',
        'name',
        'email',
        'phone',
        'code',
        'utm',
        'status',
        'birthday',
        'code_last_send_at',
        'last_visit_at',
        'confirm_phone',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->code;
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'code',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'code_last_send_at' => 'datetime',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientPromoCodes()
    {
        return $this->hasMany(ClientPromoCode::class, 'client_id', 'id')->with('promoCode');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actualClientPromoCodes()
    {
        return $this->hasMany(ClientPromoCode::class, 'client_id', 'id')
            ->whereHas('promoCode',  function ($query) {
                $query->where('only_crm', '!=', 1);
            })
            ->whereRaw('(date(`date_begin`) <= ' . Carbon::now()->toDateString()  . ' or `date_begin` is null and date(`dead_line`) >= ' . Carbon::now()->toDateString() . ' or `dead_line` is null)')
            ->where('activated','=',0)
            ->where('promo_code_id','!=',2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupClientPromoCodes(): void
    {
        $groupClientPromoCodes = [];

        $this->actualClientPromoCodes->map(function (ClientPromoCode $promoCode) use (&$groupClientPromoCodes) {
            if (array_key_exists($promoCode->promo_code_id, $groupClientPromoCodes) && $groupClientPromoCodes[$promoCode->promo_code_id] instanceof ClientPromoCode) {
                $groupClientPromoCodes[$promoCode->promo_code_id]->quantity++;
            } else {
                $groupClientPromoCodes[$promoCode->promo_code_id] = $promoCode;
                $groupClientPromoCodes[$promoCode->promo_code_id]->quantity = 1;
            }
            if((string)$promoCode->promoCode->action === PromoCodeAction::BirthDay) {
                $this->countFriendPercent++;
            }
        });

        $this->groupClientPromoCodes = $this->newCollection();

        foreach ($groupClientPromoCodes as $groupItem) {
            $this->groupClientPromoCodes->push($groupItem);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientAddresses()
    {
        return $this->hasMany(ClientAddress::class, 'client_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasActiveBlock()
    {
        return $this->hasOne(BlackListClient::class, 'client_id', 'id')->where('status_block', '=', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blackList()
    {
        return $this->hasMany(BlackListClient::class, 'client_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(ClientDevice::class, 'client_id', 'id');
    }

    /**
     * Route notifications for the Sms channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForSms($notification)
    {
        $devPhones = config("auth.dev_phones") ?? [];

        if (in_array($this->phone, $devPhones)) {
            return false;
        }

        return $this->phone;
    }

}
