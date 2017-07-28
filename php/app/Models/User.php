<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\MailResetPasswordToken;

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
        $passwords = self::pluck('password');

        return collect($data)->map(function($row) use ($role, $passwords) {
            do {
                $random_number = md5(rand(1, 100000000));
            } while ($passwords->search($random_number) !== false);

            $row['email'] = $random_number;
            $row['password'] = $random_number;
            $row['first_name'] = $random_number;
            $row['last_name'] = $random_number;

            return $role->users()->save(new User($row));
        });
    }
}
