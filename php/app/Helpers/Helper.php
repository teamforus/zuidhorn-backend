<?php

namespace App\Helpers;

class Helper {
    /**
     * @return \App\Services\BunqService\BunqService
     */
    public static function BunqService() {
        return app('bunq');
    }
}