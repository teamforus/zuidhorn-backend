<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\BudgetCategoryStoreRequest;

use App\Models\Budget;
use App\Models\Category;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetCategoryController extends Controller
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
     * @param  \App\Http\Requests\Panel\BudgetCategoryStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetCategoryStoreRequest $request)
    {
        $this->authorize('create', BudgetCategory::class);

        $budget = Budget::find($request->input('budget_id'));
        $category = Category::find($request->input('category_id'));
        
        if ($budget->categories()->attach($category->id)) {
            session()->flash('alert_default', 'New category attached!');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetCategory  $budgetCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetCategory $budgetCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetCategory  $budgetCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetCategory $budgetCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BudgetCategory  $budgetCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BudgetCategory $budgetCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetCategory  $budgetCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetCategory $budgetCategory)
    {
        $this->authorize('delete', $budgetCategory);
        
        if ($budgetCategory->unlink())
            session()->flash('alert_default', 'Budget Category deleted!');

        return redirect()->back();
    }
}
