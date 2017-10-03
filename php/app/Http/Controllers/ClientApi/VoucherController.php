<?php

namespace App\Http\Controllers\ClientApi;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Services\BlockchainApiService\Facades\BlockchainApi;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VoucherController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        //
    }

    public function activate(Request $request, Voucher $voucher)
    {
        if (!$voucher)
            return abort(404);

        $this->validate($request, [
            'email' => "required|email",
            'code' => "required|exists:vouchers,code"
            ]);

        if (!is_null($voucher->user_id))
            return response(['code' => ['Voucher already active!']], 422);

        $email = $request->input('email');
        $user = User::where(['email' => $email])->first();

        if (!$user) {
            $password = \App\Models\User::generateUid([], 'password', 4, 2);

            $user = User::create([
                'password' => Hash::make($password),
                'email' => $email,
                ]);
        } else {
            $password = false;
        }

        do {
            $private_key = User::generateUid([], 'private_key', 32);
        } while(User::wherePublicKey($private_key)->count() > 0);

        $voucher->update([
            'user_id' => $user->id,
            'private_key' => $private_key
            ]);

        $account = BlockchainApi::createAccount(
            $voucher->private_key,
            $voucher->amount);

        $voucher->update([
            'public_key' => $account['address']
            ]);

        $scope = compact('voucher', 'email', 'password');

        Mail::send('emails.activate', $scope, function ($message) use ($voucher) {
            $message->to($voucher->user->email);
            $message->subject('Voucher activation');
        });

        return [];
    }

    public function target(Request $request)
    {
        $user = $request->user();
        $voucher = $user->vouchers->first();

        return [
            'funds' => number_format($voucher ? $voucher->getAvailableFunds() : 0, 2, ',', '.')
            ];
    }

    public function getQrCode(Request $request) {
        $user = $request->user();
        $voucher = $user->vouchers->first();

        return "data:image/png;base64, " . 
        base64_encode(QrCode::format('png')
            ->margin(1)->size(300)->generate($voucher->public_key));
    }

    public function sendQrCodeEmail(Request $request) {
        $user = $request->user();
        $voucher = $user->vouchers->first();
        $email = $user->email;
        $password = false;

        $scope = compact('voucher', 'email', 'password');

        Mail::send('emails.activate', $scope, function ($message) use ($voucher) {
            $message->to($voucher->user->email);
            $message->subject('Voucher activation');
        });

        return [];
    }
}
