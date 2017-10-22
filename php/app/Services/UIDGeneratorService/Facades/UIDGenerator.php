<?php

namespace App\Services\UIDGeneratorService\Facades;

use Illuminate\Support\Facades\Facade;

class UIDGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'uid_generator';
    }
}
