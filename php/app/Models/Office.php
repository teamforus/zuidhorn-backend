<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use Traits\HasMediaTrait;
    use Traits\Urls\OfficeUrlsTrait;

    protected $fillable = [
        'shop_keeper_id', 'address', 'phone', 'email', 'lon', 'lat', 
        'parsed'
    ];

    protected $hidden = [
        '_original', '_preview'
    ];

    protected $with = [
        'schedules'
    ];

    protected $media_size = [
        'preview' => [200, 130],
        'original' => [1920, 1080],
    ];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function schedules() 
    {
        return $this->hasMany('App\Models\OfficeSchedule');
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
    
    public static function getCoordinates($address)
    {
        // replace all the white space with "+" sign to match with google search pattern
        $address = str_replace(" ", "+", $address); 
         
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";
         
        $response = file_get_contents($url);
         
        //generate array object from the response from the web
        $json = json_decode($response,TRUE); 
        
        return $json['results'][0]['geometry']['location']; 
    }

    public function updateCoordinates() {
        $coordinates = Office::getCoordinates($this->address);

        return $this->update([
            'lon' => $coordinates['lng'],
            'lat' => $coordinates['lat'],
        ]);
    }
}