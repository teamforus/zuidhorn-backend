<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTransaction extends Model
{
    use Traits\Urls\VoucherTransactionUrlsTrait;
    
    protected $fillable = ['voucher_id', 'amount'];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }
}
