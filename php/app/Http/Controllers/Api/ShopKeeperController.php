<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthSignUpRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Role;
use App\Models\ShopKeeper;
use App\Models\ShopKeeperDevice;
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

    public function signUp(AuthSignUpRequest $request)
    {
        $role = Role::where('key', 'shop-keeper')->first();

        $device_id = $request->header('Device-Id');

        $last_user_id = User::orderBy('id', 'DESC')->first();
        $last_user_id = ($last_user_id ? $last_user_id->id : 0);
        $new_user_id = $last_user_id + 1;

        do {
            $password = User::generateUid([], 'password', 8);
        } while(User::wherePassword(bcrypt($password))->count() > 0);

        $user = $role->users()->save(new User([
            'id'            => $new_user_id,
            'first_name'    => 'ShopKeeper',
            'last_name'     => '#' . str_pad($new_user_id, 3, 0, STR_PAD_LEFT),
            'email'         => $request->input('email'),
            'password'      => Hash::make($password),
            ]));
        
        $shopKeeper = ShopKeeper::create([
            'name'              => 'ShopKeeper #' . $new_user_id,
            'user_id'           => $user->id,
            'iban'              => $request->input('iban'),
            'kvk_number'        => $request->input('kvk_number'),
            'bussines_address'  => 'N\A',
            'phone_number'      => 'N\A',
            'state'             => 'pending',
            ]);

        $shopKeeper->shop_keeper_devices()->save(new ShopKeeperDevice([
            'device_id' => $device_id,
            'status'    => 'approved'
            ]));

        Mail::send(
            'emails.shopkeeper-account-details', compact('user', 'password'), function ($message) use ($user) {
                $message->to($user->email, $user->full_name);
                $message->subject('Forus - shopkeeper account details.');
                $message->priority(3);
            });

        return ['access_token' => $user->createToken('Token Name')->accessToken];
    }
}
