<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function user(Request $request) {
        return $request->user()->load('permissions');
    }
}
