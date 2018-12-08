<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Shop.
 *
 * @package namespace App\Models;
 */
class Shop extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'access_token',
        'shop_name',
        'email',
        'owner',
        'plan',
        'free_pass',
        'last_checked'
    ];

    protected $casts = [
        'free_pass' => 'boolean',
    ];

    protected $dates = [
        'last_checked'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne('App\Models\Setting');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billing()
    {
        return $this->hasOne('App\Models\Billing');
    }

    public function oldBills()
    {
        return $this->hasMany(Billing::class, 'shop_name', 'name');
    }

    public function lastBill()
    {
        return $this->oldBills()->withTrashed()->where('deleted_at', '!=', null)->get()->last();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialMedia()
    {
        return $this->hasOne('App\Models\SocialMedia');
    }
    /**
     * helper method to check if shop should get free access to our app
     * @return bool
     */
    public function shouldGetFreePass()
    {
        // ----- check if user should get free pass
        return false;
    }
}
