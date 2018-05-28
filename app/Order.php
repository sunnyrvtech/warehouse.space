<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_url', 'account_key', 'access_token', 'order_id', 'item_id','variant_id'
    ];
}
