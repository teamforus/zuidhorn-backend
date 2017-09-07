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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'code', 'user_buget_id', 'shop_keeper_id', 'max_amount', 'private_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'private_key'
    ];

    public function user_buget()
    {
        return $this->belongsTo('App\Models\UserBuget');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\VoucherTransaction');
    }

    public static function generateCode($cache = false)
    {
        $keys = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $rand_generator = function ($length, $keyspace)
        {
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $str .= $keyspace[random_int(0, $max)];
            }
            return $str;
        };

        do {
            $code = collect(range(0, 3))->map(function() use (
                $rand_generator, 
                $keys) 
            {
                return $rand_generator(6, $keys);
            })->implode('-');
        } while (!$cache ? (self::whereCode($code)->count() > 0) : collect($cache)->search($code));

        return $code;
    }

    public function getAvailableFunds()
    {
        return BlockchainApi::getBalance($this->code)['balance'];

        // TODO: Optimize to user blockchain 
        $funds_available = $this->user_buget->getAvailableFunds();
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
            $this->code,
            $shopKeeper->user->public_key,
            $shopKeeper->user->private_key,
            $amount
            );

        dispatch(new BunqProcessTransactionJob($transaction));

        return $transaction;
    }

}
