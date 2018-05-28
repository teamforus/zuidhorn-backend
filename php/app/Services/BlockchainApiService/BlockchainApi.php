<?php

namespace App\Services\BlockchainApiService;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\UIDGeneratorService\Facades\UIDGenerator;

class BlockchainApi
{   
    protected $api_url = "http://localhost:8500";
    private $log_path = "blockchain\ethereum-logs.log";

    public function __construct() {
        $this->api_url = env("BLOCKCHAIN_API_URL");
    }

    public function generateWallet() {
        $command = "cd " . storage_path('/bash/');
        $command .= "; ./ethereum-wallet-generator.sh;";

        try {
            $wallet = json_decode(shell_exec($command));

            if ($wallet->address == 
                '0xdcc703c0E500B653Ca82273B7BFAd8045D85a470') {
                throw new \Exception('Address is empty');
            }
        } catch (\Exception $e) {
            return null;
        }

        $wallet->passphrase = UIDGenerator::generate(32);
        
        return (array) $wallet;
    }

    public function exportWallet($wallet) {
        $endpoint = "/api/import-wallet";

        return $this->makeRequest(
            $endpoint, "post", 'json', compact('wallet'));
    }

    public function fundEther($wallet, $amount)
    {
        $endpoint = "/api/fund-ether";

        return $this->makeRequest(
            $endpoint, "post", 'json', compact('wallet', 'amount'));
    }

    /**
     * Add tokens to target wallet
     *
     * @param $wallet
     * @param $amount
     * @return mixed
     */
    public function fundTokens($wallet, $amount)
    {
        $endpoint = "/api/fund-tokens";

        return $this->makeRequest(
            $endpoint, "post", 'json', compact('wallet', 'amount'));
    }

    public function checkShopKeeperState($address)
    {
        $endpoint = "/api/shop-keeper/{$address}/state";

        return $this->makeRequest($endpoint, "get")['state'];
    }

    public function setShopKeeperState($address, $state)
    {
        $endpoint = "/api/shop-keeper/{$address}/state";

        return $this->makeRequest($endpoint, "post", 'json', [
            "state" => $state
        ]);
    }

    public function getBalance($address)
    {
        $endpoint = "/api/account/{$address}/balance";

        return $this->makeRequest($endpoint, "get");
    }

    public function requestFunds($from_public, $to_public, $to_private, $amount)
    {
        $endpoint = "/api/transaction/request-funds";

        return $this->makeRequest($endpoint, "post", 'json', [
            "from_public" => $from_public,
            "to_public" => $to_public,
            "to_private" => $to_private,
            "amount" => $amount,
        ]);
    }

    public function refund($from_public, $from_private, $to_public, $amount)
    {
        $endpoint = "/api/transaction/refund";

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
        
        $date = time();
        $date_format = date('Y-m-d H:i:s');

        try {
            $response = $client->$method(
                $this->api_url . $endpoint, [
                    $data_type => $data,
                    "headers" => [
                        "Api-Key" => env('BLOCKCHAIN_API_KEY')
                    ]
                ]);

            return json_decode((string) $response->getBody(), true);

        } catch(\Exception $e) {
            $message = $e->getMessage();

            Log::alert("Blockchain error sync error." . json_encode(compact(
                'endpoint', 'method', 'data_type', 'date', 
                'date_format', 'message'), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        }
    }
}