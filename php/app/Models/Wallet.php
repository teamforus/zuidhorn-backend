<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @property mixed $id
 * @property integer $walletable_id
 * @property string $walletable_type
 * @property string public_key
 * @property string private_key
 * @property string address
 * @property string passphrase
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class Wallet extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'walletable_id', 'walletable_type', 'public_key', 'private_key', 
        'address', 'passphrase'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'address', 'passphrase'
    ];

    // Get all of the owning walletable models.
    public function walletable() {
        return $this->morphTo();
    }

    public function export() {
        app('blockchain_api')->exportWallet($this->getSensitive());

        return $this;
    }

    public function fundEther($eth) {
        return app('blockchain_api')->fundEther($this->getSensitive(), $eth);
    }

    public function fundTokens($tokens) {
        return app('blockchain_api')->fundTokens($this->getSensitive(), $tokens);
    }

    private function getSensitive() {
        return [
            'address'       => $this->address,
            'public_key'    => $this->public_key,
            'private_key'   => $this->private_key,
            'passphrase'    => $this->passphrase,
        ];
    }
}
