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
        return Category::select(['name', 'id'])->get();
    }
}
