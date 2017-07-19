<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_category_id'];

    public function parent()
    {
        return $this->belongsTo(
            'App\Models\Category', 
            'parent_category_id');
    }

    public function childs()
    {
        return $this->hasMany(
            'App\Models\Category', 
            'parent_category_id');
    }

    public function bugets()
    {
        return $this->belongsToMany(
            'App\Models\Buget', 
            'buget_users');
    }

    public function shopers()
    {
        return $this->belongsToMany(
            'App\Models\Shoper', 
            'shoper_categories');
    }
}
