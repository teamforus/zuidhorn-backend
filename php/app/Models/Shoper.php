<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shoper extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'shoper_categories');
    }
}
