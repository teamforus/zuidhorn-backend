<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class ShopKeeperDevice extends Model
{
    use \App\Models\Traits\GenerateUidsTrait;

    protected $fillable = [
        'shop_keeper_id', 'device_id', 'status', 'approve_token'
    ];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function sendApprovalRequest()
    {
        $user = $this->shop_keeper->user;

        $scope = [];
        $scope['device'] = $this;

        Mail::send('emails.device-approve-request', $scope, function ($message) use ($user) {
            $message->to($user->email, $user->full_name);
            $message->subject('New device request');
            $message->priority(3);
        });
    }

    public function urlApproveLink()
    {
        return url('/device/approve/' . $this->approve_token);
    }
}
