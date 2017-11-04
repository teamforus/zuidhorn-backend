<?php
namespace App\Validators;

use App\Services\KvkApiService\Facades\KvkApi;

class RequireKeysValidator
{
    public function rule($attribute, $value, $parameters, $validator)
    {
        $keys = collect($value)->keys()->map(function($val) {
            return (string) $val;
        })->toArray();

        foreach ($parameters as $key) {
            if (!in_array((string) $key, $keys))
                return false;
        }

        return true;
    }
}