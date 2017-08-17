<?php

namespace App\Models\Traits;

/**
 * summary
 */
trait GenerateUidsTrait
{
    public static function generateUid($old_values = null, $key = 'uid', $block_length = 6)
    {
        $keys = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $rand_gen = function ($length, $keyspace)
        {
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $str .= $keyspace[random_int(0, $max)];
            }
            return $str;
        };

        $check_uid = function($key, $uid, $old_values) {
            if (!$old_values) {
                return self::where($key, $uid)->count() > 0;
            }

            return collect($old_values)->search($uid);
        };

        do {
            $uid = collect(range(0, 0))->map(function() use ($rand_gen, $keys, $block_length) {
                return $rand_gen($block_length, $keys);
            })->implode('-');
        } while ($check_uid($key, $uid, $old_values));

        return $uid;
    }

    public static function generateUrlUid($old_values = null, $key = 'url_uid', $block_length = 32)
    {
        return self::generateUid($old_values, $key, $block_length);
    }
}
