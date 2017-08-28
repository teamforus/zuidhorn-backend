<?php

namespace App\Services\KvkApiService;

class KvkApi
{   
    protected $api_url = "https://api.kvk.nl/";
    protected $api_key = null;

    function __construct($api_key)
    {
        $this->setApi($api_key);
    }

    public function setApi($api_key)
    {
        $this->api_key = $api_key;
    }

    public function kvkNumberData($kvk_number)
    {
        $data = false;
        
        try {
            $response = json_decode(file_get_contents(
                $this->api_url . "api/v2/profile/companies?q=" . 
                $kvk_number . "&user_key=" . $this->api_key));

            if (is_object($response))
                $data = $response;
        } catch (\Exception $e) {}

        return $data;
    }
}