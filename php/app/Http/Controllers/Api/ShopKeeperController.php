<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthSignUpRequest;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\ShopKeeper;
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
        return [
        'response' => $shopKeeper->load('user'),
        ];
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

        $last_user_id = User::orderBy('id', 'DESC')->first();
        $last_user_id = ($last_user_id ? $last_user_id->id : 0);
        $new_user_id = $last_user_id + 1;

        $user = $role->users()->save(new User([
            'id'            => $new_user_id,
            'first_name'    => 'ShopKeeper',
            'last_name'     => '#' . str_pad($new_user_id, 3, 0, STR_PAD_LEFT),
            'email'         => $request->input('email'),
            'password'      => Hash::make($request->input('password')),
            ]));
        
        ShopKeeper::create([
            'name'              => 'ShopKeeper #' . $new_user_id,
            'user_id'           => $user->id,
            'iban'              => $request->input('iban'),
            'kvk_number'        => $request->input('kvk_number'),
            'bussines_address'  => 'N\A',
            'phone_number'      => 'N\A',
            'state'             => 'pending',
            ]);

        return ['success' => true];
    }
}
