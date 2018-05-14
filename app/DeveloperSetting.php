<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeveloperSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'wsdl_url', 'percentage_product','page_size','offset'
    ];
}
