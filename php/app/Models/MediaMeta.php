<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMeta extends Model
{
    protected $fillable = ['media_id', 'type', 'value'];

    public $timestamps = false;

    public function urlPublic() {
        return asset($this->value);
    }

    public function realPath() {
        return public_path($this->value);
    }

    public function unlink() {
        $path = $this->realPath();

        if (file_exists($path))
            unlink($path);

        return $this->delete();
    }
}
