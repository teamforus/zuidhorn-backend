<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Panel\CategoryStoreRequest;
use App\Http\Requests\Panel\CategoryUpdateRequest;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage_categories');

        $rows = Category::orderBy('id');

        if ($request->input('id'))
            $rows->where('id', 'LIKE', "%{$request->input('id')}%");

        if ($request->input('name'))
            $rows->where('name', 'LIKE', "%{$request->input('name')}%");

        if ($request->input('parent_id'))
            $rows->whereParentId($request->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'categories-index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        $this->authorize('create', Category::class);

        return $this->_make('panel', 'categories-edit', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Panel\CategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $this->authorize('create', Category::class);

        if (Category::create($request->all()))
            session()->flash('alert_default', 'Category created!');

        return redirect(action('Panel\CategoryController@index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category)
    {
        $view = $category;
        $this->authorize('view', $view);

        return $this->_make('panel', 'categories-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $category)
    {
        $edit = $category;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'categories-edit', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Panel\CategoryUpdateRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $category)
    {
        $edit = $category;
        $this->authorize('update', $edit);

        if ($edit->update($request->all()))
            session()->flash('alert_default', 'Category updated!');

        return redirect(action('Panel\CategoryController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($category)
    {
        $this->authorize('delete', $category);
        
        if ($category->unlink())
            session()->flash('alert_default', 'Category deleted!');

        return redirect()->back();
    }
}