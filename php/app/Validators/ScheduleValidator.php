<?php
namespace App\Validators;

use App\Services\KvkApiService\Facades\KvkApi;

class ScheduleValidator
{
    public function rule($attribute, $value, $parameters, $validator)
    {
        $reg_ex = "/(2[0-3]|[01][0-9]):([0-5][0-9])/";

        // both are valid format
        $is_valid = preg_match($reg_ex, $value['start_time']) && 
        preg_match($reg_ex, $value['end_time']);

        // both are null
        $is_null =  ($value['start_time'] == 'none') && 
        ($value['end_time'] == 'none');

        return $is_valid || $is_null;
    }
}