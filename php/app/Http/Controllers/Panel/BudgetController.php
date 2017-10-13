<?php

namespace App\Http\Controllers\Panel;

use App\Models\Budget;

use App\Http\Requests\Panel\BudgetStoreRequest;
use App\Http\Requests\Panel\BudgetUpdateRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage_categories');

        $rows = Budget::orderBy('id');

        if ($request->input('id'))
            $rows->where('id', 'LIKE', "%{$request->input('id')}%");

        if ($request->input('name'))
            $rows->where('name', 'LIKE', "%{$request->input('name')}%");

        if ($request->input('parent_id'))
            $rows->whereParentId($request->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'budgets-index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        $this->authorize('create', Budget::class);

        return $this->_make('panel', 'budgets-edit', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Panel\BudgetStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetStoreRequest $request)
    {
        $this->authorize('create', Budget::class);

        if (Budget::create($request->all()))
            session()->flash('alert_default', 'Budget created!');

        return redirect(action('Panel\BudgetController@index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        $view = $budget;
        $this->authorize('view', $view);

        return $this->_make('panel', 'budgets-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function edit(Budget $budget)
    {
        $edit = $budget;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'budgets-edit', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Panel\BudgetUpdateRequest  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetUpdateRequest $request, Budget $budget)
    {
        $edit = $budget;

        if ($edit->update($request->all())) {
            session()->flash('alert_default', 'Budget updated!');
        }

        return redirect(action('Panel\BudgetController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        
        if ($budget->unlink())
            session()->flash('alert_default', 'Shop Keeper deleted!');

        return redirect()->back();
    }
}
