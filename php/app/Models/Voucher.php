<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;
use App\Services\BlockchainApiService\Facades\BlockchainApi;
use App\Models\ShopKeeper;

use App\Jobs\MailSenderJob;
use App\Jobs\VoucherActivateJob;
use App\Jobs\VoucherEmailQrCodeJob;
use App\Jobs\VoucherEmailActivationEmailJob;
use App\Jobs\BunqProcessTransactionJob;

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

    public function logTransaction($shop_keeper_id, $amount, $extra_amount = 0)
    {
        $shopKeeper = ShopKeeper::find($shop_keeper_id);

        $transaction = new Transaction(
            compact('shop_keeper_id', 'amount', 'extra_amount'));
        $transaction = $this->transactions()->save($transaction);

        dispatch(new BunqProcessTransactionJob($transaction));

        return $transaction;
    }

    public function activateByEmail($email) 
    {
        $this->update([
            'activation_token' => UIDGenerator::generate(128),
            'activation_email' => $email,
        ]);

        dispatch(new MailSenderJob(
            'emails.voucher-activation-email', [
                'voucher'   => $this,
            ], [
                'subject'   => 'Activate voucher',
                'to'        => $email
            ]));

        return [];
    }

    public function setOwner($user_id) 
    {
        $this->update(['user_id' => $user_id]);
        $this->load('user');

        return $this;
    }

    public function emailQrCode($email = false) 
    {
        if (!$email)
            $email = $this->user->email;
        
        return dispatch(new VoucherEmailQrCodeJob($this, $email));
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