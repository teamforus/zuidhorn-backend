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

        $bunq_service = new BunqService('e5df2765ea68eab80f51f37e08078f39467d9fd86ce3b0e6317b0d14ae2dddfc');

        $response = $bunq_service->getMonetaryAccounts();

        $monetaryAccountId = $response->{'Response'}[0]->{'MonetaryAccountBank'}->{'id'};

        $response = $bunq_service->paymentDetails($monetaryAccountId, [
            "value" => $amount,
            "currency" => "EUR",
        ], [
            "type"  => "IBAN",
            "value" => $this->shoper->iban,
            "name"  => $this->shoper->name,
        ]);

        $payment_id = $response->{'Response'}[0]->{'Id'}->{'id'};

        return $this->_make('panel', 'vouchers-view', compact('view'));
    }
}
