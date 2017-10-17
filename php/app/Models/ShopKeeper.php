<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use App\Services\BlockchainApiService\Facades\BlockchainApi;
use App\Services\KvkApiService\Facades\KvkApi;

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
        'phone', 'state', 'iban','kvk_data', "website"
    ];

    protected $hidden = [
        'kvk_data', '_original', '_preview'
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

    public function makeBlockchainAccount() {
        $account = BlockchainApi::createAccount($this->user->private_key);

        $this->user->update([
            'public_key' => $account['address']
        ]);

        return $account;
    }

    public static function registerNewShopkeeper(Request $request) {
        $role = Role::where('key', 'shop-keeper')->first();
        $device_id = $request->header('Device-Id');

        $email = $request->input('email');
        $iban = $request->input('iban');
        $kvk_number = $request->input('kvk_number');

        $kvk_data = KvkApi::kvkNumberData($kvk_number);
        $kvk_item = collect($kvk_data->data->items)->first();

        $shopkeeper_name = '';

        if (isset($kvk_item->tradeNames->businessName))
            $shopkeeper_name = [$kvk_item->tradeNames->businessName];
        elseif (isset($kvk_item->tradeNames->shortBusinessName))
            $shopkeeper_name = [$kvk_item->tradeNames->shortBusinessName];


        do {
            $password = User::generateUid([], 'password', 16);
        } while(User::wherePassword(bcrypt($password))->count() > 0);

        do {
            $private_key = User::generateUid([], 'private_key', 32);
        } while(User::wherePublicKey($private_key)->count() > 0);

        $user = $role->users()->save(new User([
            'email'         => $email,
            'password'      => Hash::make($password),
            'private_key'   => $private_key
        ]));
        
        $websites = isset($kvk_item->websites) ? $kvk_item->websites : [];
        
        $shopKeeper = ShopKeeper::create([
            'name'          => collect($shopkeeper_name)->implode(', '),
            'user_id'       => $user->id,
            'iban'          => strtoupper($iban),
            'kvk_number'    => $kvk_number,
            'state'         => 'pending',
            "website"       => collect($websites)->implode(', '),
            'kvk_data'      => json_encode($kvk_data),
        ]);

        $shopKeeper->devices()->save(new Device([
            'device_id' => $device_id,
            'status'    => 'approved'
        ]));

        $addresses = collect($kvk_data->data->items[0]->addresses);

        $addresses->each(function($office) use ($shopKeeper) {
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

        $bussines_address = '';

        if ($shopKeeper->offices()->count() > 0)
            $bussines_address = $shopKeeper->offices()->first()->address;

        $shopKeeper->update(compact('bussines_address'));

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
}
