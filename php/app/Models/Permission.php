<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'name',
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_permissions');
    }
}
