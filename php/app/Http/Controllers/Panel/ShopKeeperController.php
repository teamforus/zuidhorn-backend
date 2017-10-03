<?php

namespace App\Http\Controllers\Panel;

use \App\Http\Requests\Panel\ShopKeeperStoreRequest;
use \App\Http\Requests\Panel\ShopKeeperUpdateRequest;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\ShopKeeper;
use App\Models\Role;
use App\Models\User;

class ShopKeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage_shop-keepers');

        $rows = ShopKeeper::orderBy('id');

        if ($request->input('id'))
            $rows->where('id', 'LIKE', "%{$request->input('id')}%");

        if ($request->input('name'))
            $rows->where('name', 'LIKE', "%{$request->input('name')}%");

        if ($request->input('category_id')) {
            $ids = [];

            if ($category = Category::find($request->input('category_id')))
                $ids = $category->shop_keepers->pluck('id')->toArray();

            $rows->whereIn('id', $ids);
        }

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'shop_keepers-index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        $this->authorize('create', ShopKeeper::class);

        return $this->_make('panel', 'shop_keepers-edit', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Panel\ShopKeeperStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopKeeperStoreRequest $request)
    {
        $this->authorize('create', ShopKeeper::class);

        $user = Role::where('key', 'shop-keeper')->first()->users()->create([
            // 'first_name' => $request->input('first_name'),
            // 'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            ]);

        $shopKeeper = ShopKeeper::create([
            'user_id' => $user->id
            ]);

        $data = $request->all();

        if ($shopKeeper->update($data))
            session()->flash('alert_default', 'Shoop Keeper created!');

        if (!$shopKeeper->public_key && ($data['state'] == 'approved'))
            $shopKeeper->makeBlockchainAccount();

        return redirect(action('Panel\ShopKeeperController@index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ShopKeeper $shopKeeper)
    {
        $view = $shopKeeper;
        $this->authorize('view', $view);

        return $this->_make('panel', 'shop_keepers-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ShopKeeper $shopKeeper)
    {
        $edit = $shopKeeper;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'shop_keepers-edit', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function update(ShopKeeperUpdateRequest $request, ShopKeeper $shopKeeper)
    {
        $edit = $shopKeeper;
        $this->authorize('update', $edit);

        $data = collect($request->all())->toArray();

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        if (!$shopKeeper->public_key && ($data['state'] == 'approved'))
            $shopKeeper->makeBlockchainAccount();

        BlockchainApi::setShopKeeperState(
            $shopKeeper->user->public_key, 
            $data['state'] == 'approved');

        if ($edit->update($data) && $edit->user->update($data))
            session()->flash('alert_default', 'Shop Keeper updated!');

        return redirect(action('Panel\ShopKeeperController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeper $shopKeeper)
    {
        $this->authorize('delete', $shopKeeper);
        
        if ($shopKeeper->unlink())
            session()->flash('alert_default', 'Shop Keeper deleted!');

        return redirect()->back();
    }


    /**
     * Set ShopKeeper state to "approved"
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function stateApprove(ShopKeeper $shopKeeper)
    {
        if ($shopKeeper->update(['state' => 'approved']))
            session()->flash('alert_default', 'Shop Keeper has been approved!');

        if (!$shopKeeper->public_key)
            $shopKeeper->makeBlockchainAccount();
        
        BlockchainApi::setShopKeeperState($shopKeeper->user->public_key, true);

        return redirect()->back();
    }
}