<?php

namespace App\Http\Controllers\Panel;

use App\Models\Category;
use App\Models\ShopKeeper;
use App\Models\ShopKeeperCategory;

use App\Http\Requests\ShopKeeperCategoryStoreRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopKeeperCategoryController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopKeeperCategoryStoreRequest $request)
    {
        $this->authorize('create', ShopKeeperCategory::class);

        $shopKeeper = ShopKeeper::find($request->input('shop_keeper_id'));
        $category = Category::find($request->input('category_id'));
        
        if ($shopKeeper->categories()->attach($category->id)) {
            session()->flash('alert_default', 'New category attached!');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeperCategory  $shopKeeperCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ShopKeeperCategory $shopKeeperCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopKeeperCategory  $shopKeeperCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopKeeperCategory $shopKeeperCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeperCategory  $shopKeeperCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopKeeperCategory $shopKeeperCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopKeeperCategory  $shopKeeperCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeperCategory $shopKeeperCategory)
    {
        $this->authorize('delete', $shopKeeperCategory);
        
        if ($shopKeeperCategory->unlink())
            session()->flash('alert_default', 'Shop Keeper Category deleted!');

        return redirect()->back();
    }
}
