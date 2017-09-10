<?php

namespace App\Models\Traits;

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
            $uid = collect(range(0, $block_count - 1))->map(function() use ($block_length) {
                return bin2hex(openssl_random_pseudo_bytes($block_length / 2));
            })->implode('-');

        } while ($check_uid($key, $uid, $old_values));

        return strtoupper($uid);
    }

    public static function generateUrlUid($old_values = null, $key = 'url_uid', $block_length = 32, $block_count = 1)
    {
        return self::generateUid($old_values, $key, $block_length);
    }
}
