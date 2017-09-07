<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthSignUpRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Services\KvkApiService\Facades\KvkApi;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use App\Models\User;
use App\Models\Role;
use App\Models\ShopKeeper;
use App\Models\ShopKeeperOffice;
use App\Models\ShopKeeperDevice;
use App\Models\ShopKeeperDeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopKeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function show(ShopKeeper $shopKeeper)
    {
        if (!$shopKeeper->id)
            return response(collect([
                'message' => 'Shopkeeper not found!'
                ]), 404);

        return $shopKeeper->load('user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopKeeper $shopKeeper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopKeeper $shopKeeper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeper $shopKeeper)
    {
        //
    }

    public function registerDevice(Request $request)
    {
        $device_id = $request->header('Device-Id');
        $device_token = ShopKeeperDeviceToken::where([
            'token' => $request->input('token')
            ])->first();

        $shop_keeper = $device_token->shop_keeper;

        if (!$device_token)
            return response(collect([
                'error' => 'token-not-found',
                'message' => "Registration token was not found."
                ]), 401);

        if ($device_token->used == 1)
            return response(collect([
                'error' => 'token-used',
                'message' => "Registration token was already used."
                ]), 401);

        $device_token->update(['used' => 1]);

        $shop_keeper->shop_keeper_devices()->save(new ShopKeeperDevice([
            'device_id' => $device_id,
            'status'    => 'approved'
            ]));

        $user = $shop_keeper->user;

        return ['access_token' => $user->createToken('Token')->accessToken];
    }

    public function createDeviceToken(Request $request)
    {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        $device_token = ShopKeeperDeviceToken::firstOrCreate([
            'shop_keeper_id' => $shop_keeper->id,
            'used' => 0,
            ]);

        if (!$device_token->token)
            $device_token->update([
                'token' => ShopKeeperDeviceToken::generateUid(null, 'token', 64),
                ]);

        return $device_token;
    }

    public function signUp(AuthSignUpRequest $request)
    {
        $role = Role::where('key', 'shop-keeper')->first();

        $device_id = $request->header('Device-Id');

        $last_user_id = User::orderBy('id', 'DESC')->first();
        $last_user_id = ($last_user_id ? $last_user_id->id : 0);
        $new_user_id = $last_user_id + 1;

        $kvk_data = KvkApi::kvkNumberData($request->input('kvk_number'));

        if (!$kvk_data)
            return response(collect([
                'kvk_number' => ["Kvk number is not valid."]
                ]), 420);

        $shopkeeper_name = collect($kvk_data->data->items[0]->tradeNames->currentNames)->implode(', ');
        $shopkeeper_websites = collect($kvk_data->data->items[0]->websites)->implode(', ');

        do {
            $password = User::generateUid([], 'password', 16);
        } while(User::wherePassword(bcrypt($password))->count() > 0);

        do {
            $private_key = User::generateUid([], 'private_key', 32);
        } while(User::wherePublicKey($private_key)->count() > 0);

        $user = $role->users()->save(new User([
            'id'            => $new_user_id,
            'first_name'    => 'ShopKeeper',
            'last_name'     => '#' . str_pad($new_user_id, 3, 0, STR_PAD_LEFT),
            'email'         => $request->input('email'),
            'password'      => Hash::make($password),
            'private_key'   => $private_key
            ]));
        
        $shopKeeper = ShopKeeper::create([
            'name'              => 'ShopKeeper #' . $new_user_id,
            'user_id'           => $user->id,
            'iban'              => strtoupper($request->input('iban')),
            'kvk_number'        => $request->input('kvk_number'),
            'bussines_address'  => '',
            'phone_number'      => '',
            'state'             => 'pending',
            'kvk_data'          => json_encode($kvk_data),
            ]);

        $shopKeeper->shop_keeper_devices()->save(new ShopKeeperDevice([
            'device_id' => $device_id,
            'status'    => 'approved'
            ]));

        $bussines_address = '';

        collect($kvk_data->data->items[0]->addresses)->each(function($office) use ($shopKeeper, &$bussines_address) {
            $office_address = collect([$office->street, $office->houseNumber, 
                $office->houseNumberAddition, $office->postalCode, 
                $office->city, $office->country])->filter()->implode(', ');

            if ($bussines_address == '')
                $bussines_address = $office_address;

            $shopKeeper->shop_keeper_offices()->save(new ShopKeeperOffice([
                'address' => $office_address,
                'lon' => $office->gpsLongitude,
                'lat' => $office->gpsLatitude,
                ]));
        });

        $shopKeeper->update(compact('bussines_address'));

        $account =  BlockchainApi::createShopKeeper($private_key);

        $user->update([
            'public_key' => $account['address']
        ]);

        return ['access_token' => $user->createToken('Token')->accessToken];
    }
}
