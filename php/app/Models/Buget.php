<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buget extends Model
{
    use Traits\Urls\BugetUrlsTrait;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'buget_users');
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'buget_categories');
    }
}
