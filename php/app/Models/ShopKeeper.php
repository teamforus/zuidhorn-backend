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
}
