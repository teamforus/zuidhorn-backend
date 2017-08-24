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
            'buget_categories');
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

        if ($this->_preview)
            $this->_preview->unlink();

        if ($this->_original)
            $this->_original->unlink();

        return $this->delete();
    }

    public function _preview() {
        return $this->morphOne('App\Models\Media', 'mediable')->whereType('preview');
    }

    public function _original() {
        return $this->morphOne('App\Models\Media', 'mediable')->whereType('original');
    }

    public function urlOriginal()
    {
        // return uploaded avatar
        if ($this->_original)
            return $this->_original->_original->urlPublic('original');

        // return default avatar
        return false;
    }

    public function urlPreview()
    {
        // return uploaded avatar
        if ($this->_preview)
            return $this->_preview->_preview->urlPublic('preview');

        // return default avatar
        return false;
    }
}
