<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTransaction extends Model
{
    protected $fillable = ['voucher_id', 'amount'];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }
}
