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
    'first_name', 'last_name', 'bsn_hash', 'email', 'password',
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

    public static function generateCitizensByHash($data)
    {
        return collect($data)->map(function($row) {
            do {
                $password = md5(rand(1, 100000000));
            } while (self::wherePassword($password)->count() > 0);

            do {
                $email = md5(rand(1, 100000000));
            } while (self::whereEmail($email)->count() > 0);

            $row['email'] = $email;
            $row['password'] = $password;

            if (!$user = self::whereBsnHash($row['bsn_hash'])->first()) {
                $user = Role::where('key', 'citizen')->first()->users()->save(
                    new User($row));
            }

            return $user;
        });
    }
}
