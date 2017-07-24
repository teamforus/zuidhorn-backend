<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shoper extends Model
{
    use Traits\Urls\ShoperUrlsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name'
    ];

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'shoper_categories');
    }
}
