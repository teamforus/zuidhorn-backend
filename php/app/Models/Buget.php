<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buget extends Model
{
    use Traits\Urls\BugetUrlsTrait;

    protected $fillable = ['name', 'amount_per_child'];

    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'buget_users');
    }

    public function buget_categories()
    {
        return $this->hasMany('App\Models\BugetCategory');
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'buget_categories');
    }

    public function unlink()
    {
        return $this->delete();
    }
}
