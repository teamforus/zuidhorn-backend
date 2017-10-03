<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use \App\Models\Traits\GenerateUidsTrait;

    protected $fillable = ['token', 'authorized', 'shop_keeper_id', 'ip'];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public static function cleanupExpired() 
    {
        $date = (new \DateTime)->modify('-60 minutes')->format('Y-m-d H:i:s');

        DeviceToken::where(
            'updated_at', '<=', $date
        )->delete();
    }
}
