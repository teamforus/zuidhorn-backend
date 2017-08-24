<?php

namespace App\Http\Controllers\ClientApi;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all()->map(function($category) {
            $shopkeepers = $category->shop_keepers->map(function($shop_keeper) {
                $offices = $shop_keeper->shop_keeper_offices->map(function($office) {
                    return [
                    'id' => $office->id,
                    'address' => $office->address,
                    'lon' => $office->lon,
                    'lat' => $office->lat,
                    'preview' => $office->urlPreview(),
                    'original' => $office->urlOriginal(),
                    ];
                });

                return [
                'id' => $shop_keeper->id,
                'name' => $shop_keeper->name,
                'phone' => $shop_keeper->phone,
                'categories' => $shop_keeper->categories->pluck('name')->implode(', '),
                'offices' => $offices,
                ];
            });

            return [
            'id' => $category->id,
            'name' => $category->name,
            'preview' => $category->urlPreview(),
            'original' => $category->urlOriginal(),
            'shopkeepers' => $shopkeepers
            ];
        });

        return $categories;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
