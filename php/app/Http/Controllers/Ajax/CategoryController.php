<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Category;

class CategoryController extends Controller
{
    public function getSelectOptions(Request $req)
    {
        $options = collect(Category::hierarchicalSelectOptions());

        $response = $options->map(function($name, $id) {
            $name = htmlspecialchars_decode($name);
            return compact('id', 'name');
        })->values();

        return compact('response');
    }
}
