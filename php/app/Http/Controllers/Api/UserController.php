<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use \App\Models\ShopKeeper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Get current user details
     * @param  Request $request Request details
     * @return Array           
     */
    public function curentUser(Request $request)
    {
        $user = $request->user();

        $user->shop_keeper = ShopKeeper::whereUserId($user->id)->first();
        $user->role = $user->roles()->first();

        $user->shop_keeper->preview = $user->shop_keeper->urlPreview();
        $user->shop_keeper->original = $user->shop_keeper->urlOriginal();

        return $user->toArray();
    }
}
