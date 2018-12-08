<?php

namespace App\Models;

/**
 * Class Setting.
 *
 * @package namespace App\Models;
 */
class Setting extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'enabled',
        'blocking_mode',
        'eu_only',
        'position',
        'comply',
        'color_bg',
        'color_button'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function definitions()
    {
        return $this->hasMany('App\Models\Definition');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

}
