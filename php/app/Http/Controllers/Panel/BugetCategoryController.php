<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\BugetCategoryStoreRequest;

use App\Models\Buget;
use App\Models\Category;
use App\Models\BugetCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BugetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BugetCategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BugetCategoryStoreRequest $request)
    {
        $this->authorize('create', BugetCategory::class);

        $buget = Buget::find($request->input('buget_id'));
        $category = Category::find($request->input('category_id'));
        
        if ($buget->categories()->attach($category->id)) {
            session()->flash('alert_default', 'New category attached!');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BugetCategory  $bugetCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BugetCategory $bugetCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BugetCategory  $bugetCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BugetCategory $bugetCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BugetCategory  $bugetCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BugetCategory $bugetCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BugetCategory  $bugetCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(BugetCategory $bugetCategory)
    {
        $this->authorize('delete', $bugetCategory);
        
        if ($bugetCategory->unlink())
            session()->flash('alert_default', 'Buget Category deleted!');

        return redirect()->back();
    }
}
