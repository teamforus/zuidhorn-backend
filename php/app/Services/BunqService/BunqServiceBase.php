<?php
namespace App\Services\BunqService;

use App\Services\BunqService\Request;
use App\Services\BunqService\BunqRequest;

class BunqServiceBase 
{
    const VERBOSE = FALSE;
    
    /**
    * The RSA keys are 2048 bits long.
    */
    const KEY_BITS = 2048;

    /**
    * The cryptographic asymmetric key algorithm used is RSA.
    */
    const KEY_TYPE = OPENSSL_KEYTYPE_RSA;
    
    protected $clientPublicKey = null;
    protected $clientPrivateKey = null;
    
    protected $installationToken = null;
    protected $api_key = null;
    protected $device_description = "My Device Description";
    
    protected $sessionServerToken = null;
    protected $userId = null;
    
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        
        $response = $this->makeInstallation();
        self::echoResponse($response);
        
        $this->installationToken = $response->{'Response'}[1]->{'Token'}->{'token'};
        
        $response = $this->makeDeviceServer($this->installationToken);
        self::echoResponse($response);
        
        $response = $this->makeServerSession();
        self::echoResponse($response);
                
        $this->sessionServerToken = $response->{'Response'}[1]->{'Token'}->{'token'};
        
        if (property_exists($response->{'Response'}[2], 'UserCompany')) {
            $this->userId = $response->{'Response'}[2]->{'UserCompany'}->{'id'};
        } else {
            $this->userId = $response->{'Response'}[2]->{'UserPerson'}->{'id'};
        }
    }
    
    private function makeInstallation()
    {
        $keyPair = self::createKeyPair();
        
        $this->clientPublicKey = $keyPair->publicKey;
        $this->clientPrivateKey = $keyPair->privateKey;

        $arrayBody = [
            'client_public_key' => $this->clientPublicKey
        ];
        
        return BunqRequest::makeRequest(
            'installation', 
            BunqRequest::METHOD_POST, 
            $arrayBody);
    }
    
    public function makeDeviceServer()
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->installationToken
        ];
        
        $arrayBody = [
            'secret'        => $this->api_key,
            'description'   => $this->device_description
        ];
        
        return BunqRequest::makeRequest(
            'device-server', 
            BunqRequest::METHOD_POST, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    public function makeServerSession()
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->installationToken
        ];
        
        $arrayBody = [
            'secret'        => $this->api_key
        ];
        
        return BunqRequest::makeRequest(
            'session-server', 
            BunqRequest::METHOD_POST, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    protected static function createKeyPair()
    {
        $config = [
            'private_key_bits' => self::KEY_BITS,
            'private_key_type' => self::KEY_TYPE
        ];

        // Create the private and public key.
        $resourceIdentifier = openssl_pkey_new($config);

        openssl_pkey_export($resourceIdentifier, $privateKey);
        $publicKey = openssl_pkey_get_details($resourceIdentifier)['key'];

        $pair = new \stdClass();
        $pair->privateKey = $privateKey;
        $pair->publicKey = $publicKey;

        // Clean up the key resource.
        openssl_pkey_free($resourceIdentifier);

        return $pair;
    }

    /**
    * Used for debugging.
    *
    * @param $responseArray
    */
    protected static function echoResponse($responseArray)
    {
        if (!self::VERBOSE)
            return;
        
        $responseString = PHP_EOL . chr(27) . '[33mResponse: ' . chr(27) . '[0m' . PHP_EOL;

        if ($responseArray === false) {
            $responseString = $responseString . 'ERROR: cURL returned "false".';
        } else {
            $responseString = $responseString . json_encode($responseArray, JSON_PRETTY_PRINT);
        }

        echo $responseString . PHP_EOL;
    }
}