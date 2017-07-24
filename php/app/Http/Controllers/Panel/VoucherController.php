<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function getList(Request $req)
    {
        $this->authorize('manage_vouchers');

        $rows = Voucher::orderBy('id');

        if ($req->input('id'))
            $rows->where('id', 'LIKE', "%{$req->input('id')}%");

        if ($req->input('name'))
            $rows->where('name', 'LIKE', "%{$req->input('name')}%");

        if ($req->input('parent_id'))
            $rows->whereParentId($req->input('parent_id'));

        $rows = $rows->paginate(10);
        
        return $this->_make('panel', 'vouchers-index', compact('rows'));
    }

    public function getView(Request $req, $view = FALSE)
    {
        $this->authorize('view', $view);

        return $this->_make('panel', 'vouchers-view', compact('view'));
    }
}
