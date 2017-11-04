<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use App\Services\BlockchainApiService\Facades\BlockchainApi;
use App\Services\KvkApiService\Facades\KvkApi;

use App\Jobs\ShoKeeperInitializeWalletCodeJob;
use App\Jobs\BlockchainRequestJob;
use App\Jobs\MailSenderJob;
use App\Models\OfficeSchedule;

class ShopKeeper extends Model
{
    use Traits\HasMediaTrait;
    use Traits\Urls\ShopKeeperUrlsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'kvk_number', 'btw_number', 'bussines_address', 
        'phone', 'state', 'iban', 'iban_name', 'kvk_data', "website"
    ];

    protected $hidden = [
        'kvk_data', '_original', '_preview', 'pivot'
    ];

    protected $media_size = [
        'preview' => [200, 200],
        'original' => [1000, 1000],
    ];

    /**
     * Return list all available states
     * 
     * @return Illuminate\Support\Collection
     */
    public static function availableStates()
    {
        return collect([
            'pending'   => 'Pending',
            'declined'  => 'Declined',
            'approved'  => 'Approved',
        ]);
    }

    public function wallet() {
        return $this->morphOne('App\Models\Wallet', 'walletable');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function shop_keeper_categories()
    {
        return $this->hasMany('App\Models\ShopKeeperCategory');
    }

    public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function device_tokens()
    {
        return $this->hasMany('App\Models\DeviceToken');
    }

    public function offices()
    {
        return $this->hasMany('App\Models\Office');
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'shop_keeper_categories');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund');
    }

    public function unlink()
    {
        $this->user->unlink();
        
        return $this->delete();
    }

    public function checkDevice($device_id)
    {
        return $this->devices()->where([
            'device_id' => $device_id,
        ])->first();
    }

    public static function signUpShopkeeper(Request $request) {
        // user type and new device
        $role = Role::where('key', 'shop-keeper')->first();
        $device_id = $request->header('Device-Id');

        // new user details
        $email = $request->input('email');
        $name = $request->input('name', '');
        $iban = $request->input('iban');
        $kvk_number = $request->input('kvk_number');

        // fetch kvk api
        $kvk_data = KvkApi::kvkNumberData($kvk_number);
        $kvk_item = collect($kvk_data->data->items)->first();

        if (isset($kvk_item->tradeNames->businessName))
            $shop_name = $kvk_item->tradeNames->businessName;
        elseif (isset($kvk_item->tradeNames->shortBusinessName))
            $shop_name = $kvk_item->tradeNames->shortBusinessName;

        // generate random password
        do {
            $password = User::generateUid([], 'password', 16);
        } while(User::wherePassword(bcrypt($password))->count() > 0);

        // save user
        $user = $role->users()->save(new User([
            'name'          => $name,
            'email'         => $email,
            'password'      => Hash::make($password)
        ]));

        // get websites if any
        $websites = isset($kvk_item->websites) ? $kvk_item->websites : [];

        $shopKeeper = ShopKeeper::create([
            'name'          => $shop_name,
            'user_id'       => $user->id,
            'iban'          => strtoupper($iban),
            'iban_name'     => $name != '' ? $name : $shop_name,
            'kvk_number'    => $kvk_number,
            'state'         => 'pending',
            "website"       => collect($websites)->implode(', '),
            'kvk_data'      => json_encode($kvk_data),
        ]);

        $shopKeeper->devices()->save(new Device([
            'device_id' => $device_id,
            'status'    => 'approved'
        ]));

        $shopKeeper->load('user');

        if (isset($kvk_data->data->items[0]->addresses))
            $addresses = collect($kvk_data->data->items[0]->addresses);
        else
            $addresses = collect([]);

        $addresses->each(function($office) use ($shopKeeper) {
            if ($office->type != "vestigingsadres")
                return;

            $office_address = collect([$office->street, $office->houseNumber, 
                $office->houseNumberAddition, $office->postalCode, 
                $office->city, $office->country])->filter()->implode(', ');

            $office = $shopKeeper->offices()->save(new Office([
                'address'   => $office_address,
                'lon'       => $office->gpsLongitude,
                'lat'       => $office->gpsLatitude,
                'phone'     => '',
                'email'     => '',
            ]));

            for ($week_day = 1; $week_day <= 5; $week_day++) {
                $office->schedules()->save(
                    new OfficeSchedule(compact('week_day')));
            }
        });

        // create shopkeeper's wallet and add 
        // ether for transactions
        $shopKeeper->generateWallet();
        
        ShoKeeperInitializeWalletCodeJob::dispatch($shopKeeper);

        return $shopKeeper;
    }

    public function _preview() {
        return $this->morphOne('App\Models\Media', 'mediable')->whereType('preview');
    }

    public function _original() {
        return $this->morphOne('App\Models\Media', 'mediable')->whereType('original');
    }

    public function urlOriginal()
    {
        // return uploaded avatar
        if ($this->_original)
            return $this->_original->_original->urlPublic('original');

        // return default avatar
        return false;
    }

    public function urlPreview()
    {
        // return uploaded avatar
        if ($this->_preview)
            return $this->_preview->_preview->urlPublic('preview');

        // return default avatar
        return false;
    }

    public function changeState($state) {
        $shopkeeper = $this;
        $shopkeeper->update(['state' => $state]);

        if (!$shopkeeper->wallet)
            throw new \Exception('No wallet, please create wallet first.');

        $subjects = [
            'pending'   => 'State changed',
            'declined'  => 'Declined',
            'approved'  => 'welkom bij kindpakket Zuidhorn',
        ];

        MailSenderJob::dispatch(
            'emails.shopkeeper-state-changed', [
                'state'     => $state
            ], [
                'to'        => $shopkeeper->user->email,
                'subject'   => $subjects[$state],
            ]
        )->onQueue('high');

        dispatch(new BlockchainRequestJob(
            'setShopKeeperState', 
            [$this->wallet->address, $state == 'approved']
        ));

        return $this;
    }

    public function generateWallet() {
        if ($this->wallet)
            return $this->wallet;
        
        $this->wallet()->create(BlockchainApi::generateWallet());
        $this->load('wallet');

        return $this->wallet;
    }

    public function getBlockchainState() {
        if ($this->wallet) 
            return BlockchainApi::checkShopKeeperState($this->wallet->address);

        return false;
    }
}
