<?php

namespace App\Services\BlockchainApiService;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class BlockchainApi
{   
    protected $api_url = "http://localhost:8500";

    function __construct()
    {

    }

    public function batchVouchers($data)
    {
        $endpoint = "{$this->api_url}/api/voucher/batch";

        return $this->makeRequest($endpoint, "post", 'json', [
            'data' => $data
            ]);
    }

    public function createAccount($private, $funds = false)
    {
        $endpoint = "{$this->api_url}/api/account";

        return $this->makeRequest($endpoint, "post", 'json', [
            "private" => $private,
            "funds" => $funds,
            ]);
    }

    public function checkShopKeeperState($address)
    {
        $endpoint = "{$this->api_url}/api/shop-keeper/{$address}/state";

        return $this->makeRequest($endpoint, "get");
    }

    public function setShopKeeperState($address, $state)
    {
        $endpoint = "{$this->api_url}/api/shop-keeper/{$address}/state";

        return $this->makeRequest($endpoint, "post", 'json', [
            "state" => $state
            ]);
    }

    public function getBalance($address)
    {
        $endpoint = "{$this->api_url}/api/account/{$address}/balance";

        return $this->makeRequest($endpoint, "get");
    }

    public function requestFunds($from_public, $to_public, $to_private, $amount)
    {
        $endpoint = "{$this->api_url}/api/transaction/request-funds";

        return $this->makeRequest($endpoint, "post", 'json', [
            "from_public" => $from_public,
            "to_public" => $to_public,
            "to_private" => $to_private,
            "amount" => $amount,
            ]);
    }

    public function refund($from_public, $from_private, $to_public, $amount)
    {
        $endpoint = "{$this->api_url}/api/transaction/refund";

        return $this->makeRequest($endpoint, "post", 'json', [
            "from_public" => $from_public,
            "from_private" => $from_private,
            "to_public" => $to_public,
            "amount" => $amount,
            ]);
    }

    public function makeRequest(
        $endpoint, 
        $method = 'get', 
        $data_type = 'form_params', 
        $data = []
        ) {
        $client = new Client();

        $response = $client->$method($endpoint, [$data_type => $data]);

        return json_decode((string) $response->getBody(), true);
    }
}