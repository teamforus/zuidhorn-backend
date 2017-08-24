<?php

namespace App\Http\Controllers\ClientApi;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'email' => "required|email"
            ]);

        if ($voucher->user_buget->user->password != 
            $voucher->user_buget->user->email)
            return response(['code' => ['Voucher already active!']], 422);

        $email = $request->input('email');
        $password = \App\Models\User::generateUid([], 'password', 10);

        $voucher->user_buget->user->update([
            'password' => Hash::make($password),
            'email' => $email,
            ]);

        $scope = compact('voucher', 'email', 'password');

        Mail::send('emails.activate', $scope, function ($message) use ($voucher) {
            $message->to($voucher->user_buget->user->email);
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
}
