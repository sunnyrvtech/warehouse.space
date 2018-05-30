<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeveloperSetting extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'warehouse_number', 'account_key', 'percentage_product', 'page_size', 'offset'
    ];      
    
    public function get_user(){
         return $this->hasOne('App\User', 'id','user_id');
    }

}
