<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBuget extends Model
{
    protected $fillable = ['user_id', 'buget_id', 'amount'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function buget()
    {
        return $this->belongsTo('App\Models\Buget');
    }

    public function vouchers()
    {
        return $this->hasMany('App\Models\Voucher');
    }

    public function transactions()
    {
        return $this->hasManyThrough(
            'App\Models\VoucherTransaction', 
            'App\Models\Voucher');
    }

    public function getAvailableFunds()
    {
        return ($this->amount - $this->transactions->sum('amount'));
    }
}
