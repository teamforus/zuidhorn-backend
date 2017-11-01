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
                $shop_keeper->offices->map(function($office) {
                    $office->preview = $office->urlPreview();
                    $office->original = $office->urlOriginal();
                });

                return [
                'id' => $shop_keeper->id,
                'name' => $shop_keeper->name,
                'phone' => $shop_keeper->phone,
                'categories' => $shop_keeper->categories->pluck('name')->implode(', '),
                'offices' => $shop_keeper->offices,
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
}
