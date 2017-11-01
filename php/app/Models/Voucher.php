<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;
use App\Services\BlockchainApiService\Facades\BlockchainApi;
use App\Models\ShopKeeper;

use App\Jobs\MailSenderJob;
use App\Jobs\VoucherActivateJob;
use App\Jobs\BlockchainRequestJob;

use App\Services\UIDGeneratorService\Facades\UIDGenerator;

class Voucher extends Model
{
    use Traits\Urls\VoucherUrlsTrait;
    use Traits\GenerateUidsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'budget_id', 'user_id', 'status', 'amount', 'activation_token',
        'activation_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'private_key', 'code', 'walletable_id', 'walletable_type', 
        'created_at', 'updated_at', 'activation_token', 'activation_email'
    ];

    public function wallet() {
        return $this->morphOne('App\Models\Wallet', 'walletable');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function budget() {
        return $this->belongsTo('App\Models\Budget');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function getAvailableFunds()
    {
        if (is_null($this->user_id))
            return $this->amount;

        return $this->amount - $this->transactions()->where(
            'status', '!=', 'refunded'
        )->sum('amount');

        return floatval(min($max_amount, $funds_available));
    }

    public function makeTransaction(
        $shop_keeper_id, 
        $amount, 
        $extra_amount = 0
    ) {
        // generate transaction
        $transaction = $this->transactions()->save(new Transaction(
            compact('shop_keeper_id', 'amount', 'extra_amount')
        ));

        // send email
        MailSenderJob::dispatch(
            'emails.voucher-transaction-done', [
                'transaction' => $transaction,
            ], [
                'to'        => $transaction->voucher->user->email,
                'subject'   => 'gebruik gemaakt kindpakket budget',
            ]
        )->onQueue('high');

        // add transaction in to blockchain
        dispatch(new BlockchainRequestJob(
            'requestFunds', [
                $transaction->voucher->wallet->address,
                $transaction->shop_keeper->wallet->address,
                $transaction->shop_keeper->wallet->passphrase,
                $transaction->amount
            ]));

        return $transaction;
    }

    public function sendActivationToken($email) 
    {
        $this->update([
            'activation_token' => UIDGenerator::generate(32, 4),
            'activation_email' => $email,
        ]);

        MailSenderJob::dispatch(
            'emails.voucher-activation-email', [
                'voucher'   => $this,
            ], [
                'subject'   => 'activeer uw kindpakket account',
                'to'        => $email
            ]
        )->onQueue('high');

        return [];
    }

    public function emailQrCode($email = false) 
    {
        if (!$email)
            $email = $this->user->email;
        
        MailSenderJob::dispatch(
            'emails.voucher-qr-code', [
                'voucher'   => $this
            ], [
                'subject'   => 'QR-code kindpakket',
                'to'        => $email
            ]
        )->onQueue('high');

        return [];
    }

    public function getBlockchainAmount() {
        try {
            if ($this->wallet)
                return BlockchainApi::getBalance($this->wallet->address)['balance'];
        } catch(\Exception $e) {

        }

        return 0;
    }

    public function generateWallet() {
        if ($this->wallet)
            return $this->wallet;
        
        $this->wallet()->create(BlockchainApi::generateWallet());
        $this->load('wallet');

        return $this->wallet;
    }
}