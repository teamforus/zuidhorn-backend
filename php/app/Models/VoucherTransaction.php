<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;

class VoucherTransaction extends Model
{
    use Traits\Urls\VoucherTransactionUrlsTrait;
    
    protected $fillable = [
    'voucher_id', 'amount', 'shop_keeper_id', 'payment_id', 'status'];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function transactionDetails()
    {
        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->paymentDetails($monetaryAccountId, $this->payment_id);

        return json_encode($response, JSON_PRETTY_PRINT);
    }

    public function makeTransaction()
    {
        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->makePayment($monetaryAccountId, [
            "value" => (string) $this->amount,
            "currency" => "EUR",
        ], [
            "type"  => "IBAN",
            "value" => $this->shop_keeper->iban,
            "name"  => $this->shop_keeper->name,
        ]);

        return $response->{'Response'}[0]->{'Id'}->{'id'};
    }
}
