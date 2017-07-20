<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function getList(Request $req)
    {
        return $this->_make('panel', 'categories-list');
    }

    public function getEdit(Request $req, $edit = FALSE)
    {
        return $this->_make('panel', 'categories-edit', compact('edit'));
    }
}
