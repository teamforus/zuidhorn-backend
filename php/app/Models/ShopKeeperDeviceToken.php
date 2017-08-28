<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopKeeperDeviceToken extends Model
{
    use \App\Models\Traits\GenerateUidsTrait;

    protected $fillable = ['token', 'used', 'shop_keeper_id'];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }
}
