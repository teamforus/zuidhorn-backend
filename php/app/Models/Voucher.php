<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BunqService\BunqService;
use App\Jobs\BunqProcessTransactionJob;

class Voucher extends Model
{
    use Traits\Urls\VoucherUrlsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'code', 'user_buget_id', 'shoper_id', 'max_amount', 'category_id', 
    'status'
    ];

    public function user_buget()
    {
        return $this->belongsTo('App\Models\UserBuget');
    }

    public function shoper()
    {
        return $this->belongsTo('App\Models\Shoper');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\VoucherTransaction');
    }

    public static function generateCode()
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
        } while (self::whereCode($code)->count() > 0);

        return $code;
    }

    public function getAvailableFunds()
    {
        $funds_available = $this->user_buget->getAvailableFunds();
        $max_amount = $this->max_amount;

        if (is_null($max_amount))
            $max_amount = $funds_available;

        return floatval(min($max_amount, $funds_available));
    }

    public function logTransaction($amount)
    {
        $transaction = new VoucherTransaction(compact('amount'));
        $transaction = $this->transactions()->save($transaction);

        dispatch(new BunqProcessTransactionJob($transaction));

        return !!$transaction;
    }

}
