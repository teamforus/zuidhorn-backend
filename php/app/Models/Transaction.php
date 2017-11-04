<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

use App\Services\BunqService\BunqService;

class Transaction extends Model
{
    use Traits\Urls\TransactionUrlsTrait;
    
    protected $fillable = [
        'voucher_id', 'amount', 'extra_amount', 'shop_keeper_id', 
        'payment_id', 'status'
    ];
    
    protected $hidden = [
        'payment_id', 'voucher_id', 'shop_keeper_id', 'last_attempt_at', 'attempts'
    ];

    protected $dates = [
        'last_attempt_at', 'created_at', 'updated_at'
    ];


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

    public function makeBunqTransaction()
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

    public static function getQueue() {
        return self::orderBy('updated_at', 'ASC')
        ->where('status', '=', 'pending')
        ->where('attempts', '<', 5)
        ->where(function($query) {
            $query
            ->whereNull('last_attempt_at')
            ->orWhere('last_attempt_at', '<', Carbon::now()->subHours(8));
        });
    }

    public static function processQueue() {
        if (self::getQueue()->count() == 0)
            return null;

        while($transaction = self::getQueue()->first()) {
            $transaction->forceFill([
                'attempts'          => ++$transaction->attempts,
                'last_attempt_at'   => Carbon::now(),
            ])->save();

            try {
                $payment_id = $transaction->makeBunqTransaction();

                if (is_numeric($payment_id)) {
                    $transaction->forceFill([
                        'status'            => 'success',
                        'payment_id'        => $payment_id
                    ])->save();
                }

            } catch(\Exception $e) {
                Log::error(
                    sprintf("[%s] - %s", Carbon::now(), $e->getMessage()));
            }
        }
    }
}
