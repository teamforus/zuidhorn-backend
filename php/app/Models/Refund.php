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
        $this->updateState();

        if ($this->status == 'pending') {
            $this->requestRevoked();
        }

        return $this;
    }

    public function updateState() {
        $status = $this->bunqRequestStatus();

        if($status == 'ACCEPTED') {
            $this->requestAccepted();
        } else if ($status == 'REJECTED' || $status == 'REVOKED') {
            $this->requestRevoked($status);
        }

        return $this;
    }

    public function requestAccepted() {
        // update refund and transactions status
        $this->update(['status' => 'refunded']);
        Transaction::whereIn('id', $this->transactions->pluck('id'))->update([
            'status' => 'refunded'
        ]);

        // dispatch to blockchain
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
    }

    private function requestRevoked($current_state = FALSE) {
        // revoke bunq request, detach transactions and update status
        if ($current_state != 'REVOKED') {
            $this->bunqRequestRevoke();
        }

        $this->forceFill(['status' => 'revoked'])->save();
    }

    private function bunqRequestRevoke() {
        if (!$this->bunq_request_id)
            return false;

        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->revokePaymentRequest($monetaryAccountId, $this->bunq_request_id);

        return $response;
    }

    public function getBunqUrl() {
        if (!$this->bunq_request_id)
            $this->bunqRequestCreate();

        $bunq_details = $this->bunqRequestDetails();
        $status = $bunq_details->RequestInquiry->status;

        Log::info('bunq - status :' . json_encode($bunq_details, JSON_PRETTY_PRINT));

        if ($status == 'PENDING')
            return $bunq_details->RequestInquiry->bunqme_share_url;

        if ($status == 'ACCEPTED') {
            $this->requestAccepted();
            return "paid";
        }

        if ($status == 'REJECTED' || $status == 'REVOKED') {
            $this->requestRevoked($status);
        }

        return false;
    }

    public function bunqRequestStatus() {
        $bunq_details = $this->bunqRequestDetails();

        if ($bunq_details)
            return $bunq_details->RequestInquiry->status;

        return false;
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

    public static function getQueue($exclude = []) {
        return self::orderBy('updated_at', 'ASC')
        ->where('status', '=', 'pending')
        ->whereNotIn('id', $exclude);
    }

    public static function processQueue() {
        if (self::getQueue()->count() == 0)
            return null;

        $ids = [];

        while($refund = self::getQueue($ids)->first()) {
            array_push($ids, $refund->id);
            $refund->updateState();
        }
    }
}
