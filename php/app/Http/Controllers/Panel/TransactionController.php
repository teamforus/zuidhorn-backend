<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\BunqService\BunqService;

class TransactionController extends Controller
{
    public function getView(Request $req, $view = FALSE)
    {
        // $this->authorize('view', $view);

        return $this->_make('panel', 'voucher-transactions-view', compact('view'));
    }
}
