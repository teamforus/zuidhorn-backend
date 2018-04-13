<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\BlockchainRequestJob;

/**
 * Class Refund
 * @property mixed $id
 * @property integer $shop_keeper_id
 * @property integer $bunq_request_id
 * @property string $status
 * @property string $link
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $transactions
 * @property ShopKeeper $shop_keeper
 * @package App\Models
 */
class Refund extends Model
{
    protected $fillable = [
        'shop_keeper_id', 'bunq_request_id', 'status', 'link'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transactions()
    {
        return $this->belongsToMany(
            'App\Models\Transaction',
            'refund_transactions');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    /**
     * Update status and revoke if still pending
     * @return $this
     */
    public function applyOrRevokeBunqRequest() {
        $this->updateState();

        if ($this->status == 'pending') {
            $this->requestRevoked();
        }

        return $this;
    }

    /**
     * Update status
     * @return $this|bool|string
     */
    public function updateState() {
        if (!$this->bunq_request_id) {
            return false;
        }

        $requestDetails = Helper::BunqService()->paymentRequestDetails(
            $this->bunq_request_id
        );

        switch ($requestDetails->getStatus()) {
            case "ACCEPTED": {
                $this->requestAccepted();
                return 'paid';
            } break;
            case "REJECTED":
            case "REVOKED": {
                $this->requestRevoked($requestDetails->getStatus());
            } break;
        }

        return $this;
    }

    /**
     * Get bunq bunq me share url
     * @return bool|string
     */
    public function getBunqUrl() {
        if (!$this->bunq_request_id) {
            $this->bunqRequestCreate();
        }

        $requestDetails = Helper::BunqService()->paymentRequestDetails(
            $this->bunq_request_id
        );

        switch ($requestDetails->getStatus()) {
            case "PENDING": {
                return $requestDetails->getBunqmeShareUrl();
            } break;
            case "ACCEPTED": {
                $this->requestAccepted();
                return 'paid';
            } break;
            case "REJECTED":
            case "REVOKED": {
                $this->requestRevoked($requestDetails->getStatus());
            } break;
        }

        return false;
    }

    /**
     * Request accepted update db
     */
    public function requestAccepted() {
        // update refund and transactions status
        $this->update([
            'status' => 'refunded'
        ]);

        $this->transactions()->update([
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

    /**
     * Request revoked update db and revoke bunq request if not already
     * @param bool $current_state
     */
    private function requestRevoked($current_state = FALSE) {
        // revoke bunq request, detach transactions and update status
        if ($current_state != 'REVOKED') {
            Helper::BunqService()->revokePaymentRequest(
                $this->bunq_request_id
            )->getValue();
        }

        $this->forceFill([
            'status' => 'revoked'
        ])->save();
    }

    /**
     * Create bunq request inquiry
     * @return mixed
     */
    private function bunqRequestCreate() {
        $bunqRequestId = Helper::BunqService()->makePaymentRequest(
            $this->transactions()->sum('amount'),
            $this->shop_keeper->user->email,
            $this->shop_keeper->name,
            'Refund.'
        );

        $this->update([
            'bunq_request_id' => $bunqRequestId
        ]);

        return $bunqRequestId;
    }

    /**
     * Get pending requests queue
     * @param array $exclude
     * @return \Illuminate\Database\Query\Builder|static
     */
    public static function getQueue($exclude = []) {
        return (new self())->orderBy('updated_at', 'ASC')
        ->where('status', '=', 'pending')
        ->whereNotIn('id', $exclude);
    }

    /**
     * Get queue and fetch requests states
     */
    public static function processQueue() {
        if (self::getQueue()->count() == 0) {
            return;
        }

        $ids = [];

        while($refund = self::getQueue($ids)->first()) {
            array_push($ids, $refund->id);
            $refund->updateState();
        }
    }
}
