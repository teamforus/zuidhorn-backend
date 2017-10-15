<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Device extends Model
{
    use \App\Models\Traits\GenerateUidsTrait;

    protected $fillable = [
        'shop_keeper_id', 'device_id', 'status'
    ];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }
}
