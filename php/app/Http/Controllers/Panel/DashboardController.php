<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getIndex(Request $req)
    {
        return $this->_make('panel', 'dashboard');
    }
}
