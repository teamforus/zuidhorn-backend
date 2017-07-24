<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryEditRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getIndex(Request $req)
    {
        $this->authorize('manage_categories');

        $rows = Category::orderBy('id');

        if ($req->input('id'))
            $rows->where('id', 'LIKE', "%{$req->input('id')}%");

        if ($req->input('name'))
            $rows->where('name', 'LIKE', "%{$req->input('name')}%");

        if ($req->input('parent_id'))
            $rows->whereParentId($req->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'categories-index', compact('rows'));
    }

    public function getView(Request $req, $view = FALSE)
    {
        $this->authorize('view', $view);

        return $this->_make('panel', 'categories-view', compact('view'));
    }

    public function getEdit(Request $req, $edit = FALSE)
    {
        $this->authorize('update', $edit);

        return $this->_make('panel', 'categories-edit', compact('edit'));
    }

    public function putEdit(CategoryEditRequest $req, $edit)
    {   
        $this->authorize('update', $edit);

        if ($edit->update($req->all()))
            session()->flash('alert_default', 'Category updated!');

        return redirect()->back();
    }

    public function getCreate(Request $req, $edit = false)
    {
        $this->authorize('create', Category::class);

        return $this->_make('panel', 'categories-edit', compact('edit'));
    }

    public function putCreate(CategoryEditRequest $req)
    {   
        $this->authorize('create', Category::class);

        if (Category::create($req->all()))
            session()->flash('alert_default', 'Category created!');

        return redirect(action('Panel\CategoryController@getIndex'));
    }


    public function getDelete(Request $req, $delete = FALSE)
    {
        $this->authorize('delete', $delete);
        
        if ($delete->unlink())
            session()->flash('alert_default', 'Category deleted!');

        return redirect()->back();
    }
}
