<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthSignUpRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Services\KvkApiService\Facades\KvkApi;

use App\Http\Requests\Api\ShopKeeperUpdateRequest;
use App\Http\Requests\Api\ShopKeeperUpdateImageRequest;

use App\Models\Media;
use App\Models\User;
use App\Models\Role;
use App\Models\ShopKeeper;
use App\Models\Office;
use App\Models\Device;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiShopKeeperBaseController;

class ShopKeeperController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return ShopKeeper::whereUserId(
            $request->user()->id
        )->first()->load('user');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function update(ShopKeeperUpdateRequest $request)
    {
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        $shopKeeper->update($request->only([
            'name'          => $request->input('name'),
            'phone'         => $request->input('phone'),
            'kvk_number'    => $request->input('kvk_number'),
            'btw_number'    => $request->input('btw_number'),
            'iban_name'     => $request->input('iban_name'),
        ]));
        
        $shopKeeper->user->update([
            'email'         => $request->input('email'),
            'name'          => $request->input('iban_name')
        ]);

        $shopKeeper->categories()->sync($request->input('categories'));

        return $this->show($request);
    }

    public function updateImage(ShopKeeperUpdateImageRequest $request) {
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // media details
        $original_type  = 'original';
        $preview_type   = 'preview';
        $mediable_type  = ShopKeeper::class;
        $mediable_id    = $shopKeeper->id;

        // upload photo
        $media_info = Media::uploadSingle(
            $original_type, $mediable_type, 'image');

        // confirm uploaded photo
        $media = Media::confirmSingle(
            $original_type, $mediable_type, 
            $mediable_id, $media_info['mediaId']);

        $media_info = Media::uploadSingle(
            $preview_type, $mediable_type, 'image');

        // confirm uploaded photo
        $media = Media::confirmSingle(
            $preview_type, $mediable_type, 
            $mediable_id, $media_info['mediaId']);

        $image = \Intervention\Image\Facades\Image::make(
            $request->file('image')
        )->encode('data-url')->encoded;

        return compact('image');
    }

    /**
     * Creates and store new device authorisation token
     * @param  Request $request Request details
     * @return Array            Response array
     */
    public function createDeviceToken(Request $request)
    {
        $token = DeviceToken::generateUid(null, 'token', 32);
        $ip = $request->ip();

        DeviceToken::cleanupExpired();
        DeviceToken::whereIp($ip)->delete($ip);
        DeviceToken::create(compact('token', 'ip'));

        return collect(compact('token'));
    }

    /**
     * Check device token state (authorized or not)
     * @param  Request $request Request details
     * @return Array            Response array
     */
    public function getDeviceTokenState(Request $request, $token)
    {
        $device_token = DeviceToken::where([
            'authorized' => 1,
            'token' => $token,
        ])->first();

        if (!$device_token)
            return ['authorized' => false];

        $user = $device_token->shop_keeper->user;
        $access_token = $user->createToken('Token')->accessToken;
        $token_type = "Bearer";

        $device_id = $request->header('Device-Id');

        $device_token->shop_keeper->devices()->save(
            new Device([
                'device_id' => $device_id,
                'status'    => 'approved'
            ]));

        $authorized = true;

        return compact('access_token', 'token_type', 'authorized');
    }

    public function authorizeDeviceToken(Request $request, $token)
    {
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        $device_token = DeviceToken::where([
            'token' => $token,
        ])->first();

        if (!$device_token)
            return response([
                'error'         => 'token-not-found',
                'message'       => 'Token not found.'
            ], 404);

        $device_token->update([
            'authorized'        => 1,
            'shop_keeper_id'    => $shopKeeper->id
        ]);

        return [];
    }

    public function signUp(AuthSignUpRequest $request)
    {
        $shopKeeper     = ShopKeeper::signUpShopkeeper($request);
        $access_token   = $shopKeeper->user->createToken('Token')->accessToken;
        $token_type     = "Bearer";

        return compact('access_token', 'token_type');
    }

    public function categories(Request $request) {
        return ShopKeeper::whereUserId(
            $request->user()->id
        )->first()->categories;
    }
}
