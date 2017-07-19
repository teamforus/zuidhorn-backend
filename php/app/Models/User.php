<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
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

    public function user_bugets()
    {
        return $this->hasMany('App\Models\UserBuget');
    }

    public function vouchers()
    {
        return $this->hasManyThrough(
            'App\Models\Voucher', 
            'App\Models\UserBuget'
            );
    }
}
