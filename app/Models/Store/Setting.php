<?php

namespace App\Models\Store;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings
 * @package App\Models\System
 *
 * @property  string $id
 * @property  string $name
 * @property  string $key
 * @property  string $group
 * @property  string $value
 * @property  bool $json
 *
 * @property  FilialSetting $valueFilials
 */
class Setting extends Model
{
   protected $fillable = [
       'name',
       'key',
       'group',
       'value',
       'json'
   ];

    /**
     * @var string[]
     */
    protected $casts = [
        'json' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valueFilials() {
        return $this->hasMany(FilialSetting::class,'setting_id','id');
    }
}
