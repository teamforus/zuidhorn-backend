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

    public function createShopKeeper($private)
    {
        $endpoint = "{$this->api_url}/api/shop-keeper";

        return $this->makeRequest($endpoint, "post", 'json', [
            "private" => $private
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