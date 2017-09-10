<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;
use App\Jobs\BunqProcessTransactionJob;
use App\Services\BlockchainApiService\Facades\BlockchainApi;
use App\Models\ShopKeeper;

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
    'code', 'public_key', 'private_key', 'buget_id', 'user_id', 'status', 'amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'private_key', 'code'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function buget() {
        return $this->belongsTo('App\Models\Buget');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\VoucherTransaction');
    }

    public function getAvailableFunds()
    {
        if (is_null($this->user_id))
            return $this->amount;
        
        return BlockchainApi::getBalance($this->public_key)['balance'];

        // TODO: Optimize to user blockchain 
        $funds_available = $this->getAvailableFunds();
        $max_amount = $this->max_amount;

        if (is_null($max_amount))
            $max_amount = $funds_available;

        return floatval(min($max_amount, $funds_available));
    }

    public function logTransaction($shop_keeper_id, $amount)
    {
        $shopKeeper = ShopKeeper::find($shop_keeper_id);

        $transaction = new VoucherTransaction(compact(
            'shop_keeper_id', 'amount'));
        $transaction = $this->transactions()->save($transaction);

        BlockchainApi::requestFunds(
            $this->public_key,
            $shopKeeper->user->public_key,
            $shopKeeper->user->private_key,
            $amount
            );

        dispatch(new BunqProcessTransactionJob($transaction));

        return $transaction;
    }

}
