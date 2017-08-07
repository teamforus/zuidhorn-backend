<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;

class VoucherTransaction extends Model
{
    use Traits\Urls\VoucherTransactionUrlsTrait;
    
    protected $fillable = ['voucher_id', 'amount', 'payment_id', 'status'];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
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
            "value" => $this->voucher->shoper->iban,
            "name"  => $this->voucher->shoper->name,
        ]);

        return $response->{'Response'}[0]->{'Id'}->{'id'};
    }
}
