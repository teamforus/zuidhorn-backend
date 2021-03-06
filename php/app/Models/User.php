<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\MailResetPasswordToken;

/**
 * Class User
 * @property mixed $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @package App\Models
 */
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
        'name', 'email', 'password'
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
     * @param string $token
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

    public function budgets()
    {
        return $this->belongsToMany(
            'App\Models\Budget', 
            'user_budgets');
    }

    public function vouchers()
    {
        return $this->hasMany('App\Models\Voucher');
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('key', $role)->count() > 0;
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('key', $permission)->count() > 0;
    }

    public function unlink()
    {
        try {
            $this->delete();
        } catch (\Exception $exception) {};
    }
}
