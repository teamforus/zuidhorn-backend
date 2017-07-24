<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function getIndexCitizens(Request $req)
    {
        $this->authorize('manage_citizens');

        $rows = Role::where('key', 'citizen')->first()->users()->orderBy('id');

        if ($req->input('id'))
            $rows->where('id', 'LIKE', "%{$req->input('id')}%");

        if ($req->input('name'))
            $rows->where('name', 'LIKE', "%{$req->input('name')}%");

        if ($req->input('parent_id'))
            $rows->whereParentId($req->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'citizens-index', compact('rows'));
    }

    public function getViewCitizen(Request $req, $view = FALSE)
    {
        $this->authorize('view', $view);

        return $this->_make('panel', 'citizens-view', compact('view'));
    }
}
