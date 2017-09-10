<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\MailResetPasswordToken;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Traits\GenerateUidsTrait, Traits\Urls\UserUrlsTrait;
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'first_name', 'last_name', 'email', 'password', 'public_key', 'private_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token', 'private_key'
    ];

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }
    
    public function roles()
    {
        return $this->belongsToMany(
            'App\Models\Role', 
            'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            'App\Models\Permission', 
            'user_permissions');
    }

    public function bugets()
    {
        return $this->belongsToMany(
            'App\Models\Buget', 
            'user_bugets');
    }

    public function vouchers()
    {
        return $this->hasMany('App\Models\Voucher');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('key', $role)->count() > 0;
    }

    public function unlink()
    {
        return $this->delete();
    }
}
