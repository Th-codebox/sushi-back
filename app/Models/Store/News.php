<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Promotion
 *
 * @package App\Models
 * @property integer $id
 * @property integer $promo_code_id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property string $sub_name
 * @property string $description
 * @property string $description_before_promo_code
 * @property string $description_after_promo_code
 * @property string $meta_title
 * @property string $h1
 * @property string $meta_description
 * @property int $sort_order
 * @property bool $status
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *

 */
class News extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_name',
        'image',
        'name',
        'description',
        'description_before_promo_code',
        'description_after_promo_code',
        'slug',
        'promo_code_id',
        'sub_name',
        'h1',
        'meta_title',
        'meta_description',
        'date',
        'status',
        'sort_order',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    public function promoCode()
    {
        return $this->hasOne(PromoCode::class, 'id', 'promo_code_id');
    }
}
