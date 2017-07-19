<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'user_buget_id', 'shoper_id', 'max_amount',
    ];

    public function user_buget()
    {
        return $this->belongsTo('App\UserBuget');
    }

    public function shoper()
    {
        return $this->belongsTo('App\Shoper');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\VoucherTransaction');
    }
}
