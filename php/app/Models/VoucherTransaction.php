<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;

class VoucherTransaction extends Model
{
    use Traits\Urls\VoucherTransactionUrlsTrait;
    
    protected $fillable = ['voucher_id', 'amount', 'payment_id'];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }

    public function transactionDetails()
    {
        $bunq_service = new BunqService('e5df2765ea68eab80f51f37e08078f39467d9fd86ce3b0e6317b0d14ae2dddfc');

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->paymentDetails($monetaryAccountId, $this->payment_id);

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
