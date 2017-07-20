<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BugetsController extends Controller
{
    public function getList(Request $req)
    {
        return $this->_make('panel', 'bugets-list');
    }
}
