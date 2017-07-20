<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CitizensController extends Controller
{
    public function getList(Request $req)
    {
        return $this->_make('panel', 'citizens-list');
    }
}
