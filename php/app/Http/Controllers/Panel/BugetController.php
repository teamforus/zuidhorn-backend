<?php

namespace App\Http\Controllers\Panel;

use App\Models\Buget;

use App\Http\Requests\Panel\BugetStoreRequest;
use App\Http\Requests\Panel\BugetUpdateRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BugetController extends Controller
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

        $rows = Buget::orderBy('id');

        if ($request->input('id'))
            $rows->where('id', 'LIKE', "%{$request->input('id')}%");

        if ($request->input('name'))
            $rows->where('name', 'LIKE', "%{$request->input('name')}%");

        if ($request->input('parent_id'))
            $rows->whereParentId($request->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'bugets-index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        $this->authorize('create', Buget::class);

        return $this->_make('panel', 'bugets-edit', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Panel\BugetStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BugetStoreRequest $request)
    {
        $this->authorize('create', Buget::class);

        if (Buget::create($request->all()))
            session()->flash('alert_default', 'Buget created!');

        return redirect(action('Panel\BugetController@index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buget  $buget
     * @return \Illuminate\Http\Response
     */
    public function show(Buget $buget)
    {
        $view = $buget;
        $this->authorize('view', $view);

        return $this->_make('panel', 'bugets-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Buget  $buget
     * @return \Illuminate\Http\Response
     */
    public function edit(Buget $buget)
    {
        $edit = $buget;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'bugets-edit', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Panel\BugetUpdateRequest  $request
     * @param  \App\Models\Buget  $buget
     * @return \Illuminate\Http\Response
     */
    public function update(BugetUpdateRequest $request, Buget $buget)
    {
        $edit = $buget;

        if ($edit->update($request->all())) {
            session()->flash('alert_default', 'Buget updated!');
        }

        return redirect(action('Panel\BugetController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buget  $buget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buget $buget)
    {
        $this->authorize('delete', $buget);
        
        if ($buget->unlink())
            session()->flash('alert_default', 'Shop Keeper deleted!');

        return redirect()->back();
    }
}
