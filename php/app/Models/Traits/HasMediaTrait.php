<?php

namespace App\Models\Traits;

/**
 * summary
 */
trait HasMediaTrait
{
    public function getMediaSize($name) {
        return isset($this->media_size[$name]) ? $this->media_size[$name] : [1000, 1000];
    }
}