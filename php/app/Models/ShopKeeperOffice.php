<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopKeeperOffice extends Model
{
    use Traits\Urls\ShopKeeperOfficeUrlsTrait;

    protected $fillable = ['shop_keeper_id', 'address', 'lon', 'lat', 'parsed'];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function unlink()
    {
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
