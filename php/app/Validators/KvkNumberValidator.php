<?php
namespace App\Validators;

use App\Services\KvkApiService\Facades\KvkApi;

class KvkNumberValidator
{
    public function rule($attribute, $value, $parameters, $validator)
    {
        $valid = FALSE;

        try {
            $valid = !!KvkApi::kvkNumberData($value);
        } catch (\Exception $e) {}

        return $valid;
    }
}