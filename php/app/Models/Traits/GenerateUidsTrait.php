<?php

namespace App\Models\Traits;
use App\Services\UIDGeneratorService\Facades\UIDGenerator;

/**
 * summary
 */
trait GenerateUidsTrait
{
    public static function generateUid($old_values = null, $key = 'uid', $block_length = 4, $block_count = 4)
    {
        $check_uid = function($key, $uid, $old_values) {
            if (is_null($old_values)) {
                return self::where($key, $uid)->count() > 0;
            }

            return in_array($uid, $old_values);
        };

        do {
            $uid = UIDGenerator::generate($block_length, $block_count);
        } while ($check_uid($key, $uid, $old_values));

        return strtoupper($uid);
    }

    public static function generateUrlUid($old_values = null, $key = 'url_uid', $block_length = 32, $block_count = 1)
    {
        return self::generateUid($old_values, $key, $block_length);
    }
}
