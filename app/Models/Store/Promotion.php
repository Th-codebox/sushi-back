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
 * @property string $color
 * @property string $mobile_image
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property string $sub_name
 * @property string $description
 * @property string $meta_title
 * @property string $text_one
 * @property string $text_two
 * @property string $img_bgr_color
 * @property string $h1
 * @property string $meta_description
 * @property int $sort_order
 * @property bool $status
 * @property Carbon $date_begin
 * @property Carbon $date_end
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *

 */
class Promotion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotions';


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
        'slug',
        'promo_code_id',
        'h1',
        'sub_name',
        'meta_title',
        'meta_description',
        'date_begin',
        'date_end',
        'status',
        'sort_order',
        'color',
        'mobile_image',
        'img_bgr_color',
        'text_one',
        'text_two',
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
        'date_begin' => 'date',
        'date_end' => 'date',
    ];

    public function promoCode()
    {
        return $this->hasOne(PromoCode::class, 'id', 'promo_code_id');
    }
}
