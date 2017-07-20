<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function getCsvParser(Request $req)
    {
        return $this->_make('panel', 'csv-parser');
    }

    public function getAdminsList(Request $req)
    {
        return $this->_make('panel', 'users-list-admins');
    }

    public function getPermissions(Request $req)
    {
        return $this->_make('panel', 'permissions');
    }
}
