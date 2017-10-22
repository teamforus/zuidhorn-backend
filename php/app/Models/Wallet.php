<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

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

    // Get all of the owning imageable models.
    public function walletable() {
        return $this->morphTo();
    }

    public function export() {
        BlockchainApi::exportWallet($this->getSensitive());

        return $this;
    }

    public function fundEther($eth) {
        return BlockchainApi::fundEther($this->getSensitive(), $eth);

        return $this;
    }

    public function fundTokens($tokens) {
        return BlockchainApi::fundTokens($this->getSensitive(), $tokens);

        return $this;
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
