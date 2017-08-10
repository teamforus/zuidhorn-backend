<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Traits\Urls\CategoryUrlsTrait;
    use Traits\SelectInputTrait;
    use Traits\SelectInputHierarchicalTrait;

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(
            'App\Models\Category', 
            'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(
            'App\Models\Category', 
            'parent_id');
    }

    public function bugets()
    {
        return $this->belongsToMany(
            'App\Models\Buget', 
            'buget_users');
    }

    public function shop_keepers()
    {
        return $this->belongsToMany(
            'App\Models\ShopKeeper', 
            'shop_keeper_categories');
    }

    public function unlink()
    {
        $this->childs->each(function($child) {
            $child->unlink();
        });

        return $this->delete();
    }
}
