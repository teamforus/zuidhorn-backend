<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopKeeper extends Model
{
    use Traits\Urls\ShopKeeperUrlsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'user_id', 'name', 'kvk_number', 'bussines_address', 'phone_number', 
    'state', 'iban'
    ];

    /**
     * Return list all available states
     * 
     * @return Illuminate\Support\Collection
     */
    public static function availableStates()
    {
        return collect([
            'pending'   => 'Pending',
            'declined'  => 'Declined',
            'approved'  => 'Approved',
            ]);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function shop_keeper_categories()
    {
        return $this->hasMany('App\Models\ShopKeeperCategory');
    }

    public function shop_keeper_devices()
    {
        return $this->hasMany('App\Models\ShopKeeperDevice');
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'shop_keeper_categories');
    }

    public function unlink()
    {
        $this->user->unlink();
        
        return $this->delete();
    }

    public function checkDevice($device_id)
    {
        return $this->shop_keeper_devices()->where([
            'device_id' => $device_id,
            ])->first();
    }

    public function requestDeviceApprovement($device_id)
    {
        $device = $this->shop_keeper_devices()->save(new ShopKeeperDevice([
            'device_id'     => $device_id,
            'approve_token' => ShopKeeperDevice::generateUid(null, 'approve_token', 32),
            ]));

        $device->sendApprovalRequest();

        return $device;
    }
}
