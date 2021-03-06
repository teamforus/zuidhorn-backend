<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Jobs\MailSenderJob;
use App\Jobs\BlockchainRequestJob;

/**
 * Class Voucher
 * @property mixed $id
 * @property string $code
 * @property integer $budget_id
 * @property integer $user_id
 * @property float $amount
 * @property string $activation_token
 * @property string $activation_email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Wallet|null $wallet
 * @property Budget $budget
 * @property User|null $user
 * @package App\Models
 */
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
        'code', 'budget_id', 'user_id', 'amount', 'activation_token',
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

        return $this->amount - $this->transactions()->whereNotIn(
            'status', ['refund', 'refunded']
        )->sum('amount');
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
            'activation_token' => app('uid_generator')->generate(32, 4),
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
        if (!$email) {
            $email = $this->user->email;
        }

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
                return app('blockchain_api')->getBalance($this->wallet->address)['balance'];
        } catch(\Exception $e) {

        }

        return 0;
    }

    public function generateWallet() {
        if ($this->wallet)
            return $this->wallet;
        
        $this->wallet()->create(app('blockchain_api')->generateWallet());
        $this->load('wallet');

        return $this->wallet;
    }
}