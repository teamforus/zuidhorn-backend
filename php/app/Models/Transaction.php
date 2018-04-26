<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @property mixed $id
 * @property integer $voucher_id
 * @property integer $amount
 * @property integer $extra_amount
 * @property integer $shop_keeper_id
 * @property integer $payment_id
 * @property string $status
 * @property Voucher $voucher
 * @property ShopKeeper $shop_keeper
 * @property Carbon $last_attempt_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
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
        return json_encode(Helper::BunqService()->paymentDetails(
            $this->payment_id
        ), JSON_PRETTY_PRINT);
    }

    public static function getQueue() {
        return (new self())->orderBy('updated_at', 'ASC')
        ->where('status', '=', 'pending')
        ->where('attempts', '<', 5)
        ->where(function($query) {
            /** @var Builder $query */
            $query
            ->whereNull('last_attempt_at')
            ->orWhere('last_attempt_at', '<', Carbon::now()->subHours(8));
        });
    }

    public static function processQueue() {
        if (self::getQueue()->count() == 0) {
            return null;
        }

        while($transaction = self::getQueue()->first()) {
            $transaction->forceFill([
                'attempts'          => ++$transaction->attempts,
                'last_attempt_at'   => Carbon::now(),
            ])->save();

            try {
                $payment_id = Helper::BunqService()->makePayment(
                    $transaction->amount,
                    $transaction->shop_keeper->iban,
                    $transaction->shop_keeper->name
                );

                if (is_numeric($payment_id)) {
                    $transaction->forceFill([
                        'status'            => 'success',
                        'payment_id'        => $payment_id
                    ])->save();
                }

            } catch(\Exception $e) {
                app('log')->error(sprintf(
                    "[%s] - %s",
                    Carbon::now(),
                    $e->getMessage()
                ));
            }
        }
    }

    public static function getBunqCosts(
        Carbon $fromDate
    ) {
        $amount = 0;

        $amount += (new self())->whereNotNull('payment_id')->where(
            'created_at', '>=', $fromDate->format('Y-m-d')
        )->count() * .1;

        $amount += (new Refund())->whereNotNull('bunq_request_id')->where(
            'created_at', '>=', $fromDate->format('Y-m-d')
        )->where('status', '=', 'refunded')->count() * .3;

        $amount += (new Refund())->whereNotNull('bunq_request_id')->where(
            'created_at', '>=', $fromDate->format('Y-m-d')
        )->where('status', '!=', 'refunded')->count() * .1;

        $amount += ($fromDate->diffInMonths(new Carbon()) * 9.99);

        return $amount;
    }
}
