<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\MailResetPasswordToken;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Traits\Urls\UserUrlsTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token',
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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function generateCitizens($data)
    {
        $role = Role::where('key', 'citizen')->first();
        $passwords = self::pluck('password')->toArray();

        $users = collect($data)->map(function($val) use (&$passwords) {
            do {
                $random_number = substr(md5(rand(1, 100000000)), 0, 10);
            } while (in_array($random_number, $passwords) !== false);

            array_push($passwords, $random_number);

            $user = [];

            $user['first_name'] = $random_number;
            $user['last_name']  = $random_number;
            $user['email']      = $random_number;
            $user['password']   = $random_number;

            return $user;
        });

        User::insert($users->toArray());

        $old_users = self::get()->keyBy('password');

        $users = $users->map(function($user) use (&$old_users) {
            if (isset($old_users[$user['password']]))
                return $old_users[$user['password']];
        })->filter(function($user) {
            return $user;
        });

        $role->users()->attach($users->pluck("id")->toArray());

        return $users;

    }

    public function unlink()
    {
        return $this->delete();
    }
}
