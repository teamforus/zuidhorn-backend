<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(Category::class)->select(['name', 'id'])->get();
    }

    public function earnings() {
        return app(Category::class)->get()->map(function($category) {
            /**
             * @var Category $category
             */
            $total = 100;

            return [
                'id' => $category->id,
                'name' => $category->name,
                'preview' => $category->urlPreview(),
                'original' => $category->urlOriginal(),
                'earnings' => compact('total')
            ];
        });
    }
}
