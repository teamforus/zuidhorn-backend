<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

use App\Services\BunqService\BunqService;
use App\Jobs\BlockchainRequestJob;

class Refund extends Model
{
    protected $fillable = [
        'shop_keeper_id', 'bunq_request_id', 'status', 'link'
    ];

    public function transactions()
    {
        return $this->belongsToMany(
            'App\Models\Transaction',
            'refund_transactions');
    }

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function applyOrRevokeBunqRequest() {
        if($this->bunqRequestFulfilled()) {
            $this->update(['status' => 'refunded']);
            $this->transactions()->update(['status' => 'refunded']);

            $this->transactions->each(function($transaction) {
                BlockchainRequestJob::dispatch(
                    'refund', [
                        $transaction->shop_keeper->wallet->address,
                        $transaction->shop_keeper->wallet->private_key,
                        $transaction->voucher->wallet->address,
                        $transaction->amount
                    ]
                );
            });
        } else {
            $this->bunqRequestRevoke();
            $this->transactions()->detach();
            $this->update(['status' => 'revoked']);
        }

        return $this;
    }

    public function getBunqUrl() {
        if (!$this->bunq_request_id)
            $this->bunqRequestCreate();

        $bunq_details = $this->bunqRequestDetails();

        if ($bunq_details->RequestInquiry->status == 'PENDING')
            return $bunq_details->RequestInquiry->bunqme_share_url;

        if ($bunq_details->RequestInquiry->status == 'ACCEPTED')
            return true;

        if ($bunq_details->RequestInquiry->status == 'REJECTED') {
            $this->update([
                'status' => 'rejected'
            ]);
        }

        return false;
    }

    public function bunqRequestFulfilled() {
        $bunq_details = $this->bunqRequestDetails();

        return $bunq_details && ($bunq_details->RequestInquiry->status == 'ACCEPTED');
    }

    private function bunqRequestCreate() {
        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->createPaymentRequest($monetaryAccountId, [
            "value" => (string) $this->transactions()->sum('amount'),
            "currency" => "EUR",
        ], [
            "type"  => "EMAIL",
            "value" => $this->shop_keeper->user->email,
            "name"  => $this->shop_keeper->name,
        ], 'Refund.');

        // Log::info('bunq - create payment request :' . json_encode($response, JSON_PRETTY_PRINT));

        $this->update([
            'bunq_request_id' => $response->Response[0]->Id->id,
        ]);

        return $response->Response[0]->Id->id;
    }

    private function bunqRequestDetails() {
        if (!$this->bunq_request_id)
            return false;

        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->verifyPaymentRequest($monetaryAccountId, $this->bunq_request_id);

        // Log::info('bunq - check payment request :' . json_encode($response, JSON_PRETTY_PRINT));

        return $response->Response[0];
    }

    private function bunqRequestRevoke() {
        if (!$this->bunq_request_id)
            return false;

        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->revokePaymentRequest($monetaryAccountId, $this->bunq_request_id);

        // Log::info('bunq - revoke payment request :' . json_encode($response, JSON_PRETTY_PRINT));

        return $response;
    }
}
