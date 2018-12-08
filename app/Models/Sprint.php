<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
  
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sprints';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'posts',
        'days',
        'from',
        'to',
        'products',
        'details',
        'type',
        'status',
        'shop_id'
    ];
}
