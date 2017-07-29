<?php
namespace App\Services\BunqService;

use App\Services\BunqService\Request;

class BunqRequest 
{
    /**
    * The Description of the Device you are using to connect to the API.
    */
    const BUNQ_DEVICE_DESCRIPTION = 'My Device Description';

    /**
    * The serviceUrl is the base URL of the API.
    * The apiVersion is the version of the API.
    */
    const BUNQ_API_SERVICE_URL = 'https://sandbox.public.api.bunq.com';
    const BUNQ_API_VERSION = 'v1';

    /**
    * These constants are used to create a unique identifier.
    */
    const UUID_FORMAT = '%s%s-%s-%s-%s-%s%s%s';
    const UUID_BINARY_LENGTH = 16;
    const UUID_CHUNK_SIZE = 4;

    /**
     * Call methods
     */
    const METHOD_POST = Request::METHOD_POST;
    const METHOD_GET = Request::METHOD_GET;
    const METHOD_PUT = Request::METHOD_PUT;
    const METHOD_DELETE = Request::METHOD_DELETE;
    
    /**
    * This geolocation is used in the headers of all requests, and is also used as location of the Cash Register we are
    * about to create.
    *
    * We use the geolocation of our office:
    */
    const BUNQ_GEOLOCATION_LATITUDE = '52.389722';
    const BUNQ_GEOLOCATION_LONGITUDE = '4.837808';
    const BUNQ_GEOLOCATION_ALTITUDE = '10';
    const BUNQ_GEOLOCATION_RADIUS = '100';
    const BUNQ_GEOLOCATION_COUNTRY = 'NL';
    
    private static $api_key = '';
    
    private static $headers = [
        Request::HEADER_REQUEST_CACHE_CONTROL => 'no-cache',
        Request::HEADER_REQUEST_USER_AGENT => 'SandboxPublicApi:TestUser',
        Request::HEADER_REQUEST_CUSTOM_LANGUAGE => 'en_US',
        Request::HEADER_REQUEST_CUSTOM_REGION => 'en_US',
    ];
    
    public static function makeRequest($endpoint, $method, $arrayBody = [], $headers = [], $signature = FALSE)
    {
        // Set Headers
        $headers = array_merge(self::$headers, $headers);
        $headers[Request::HEADER_REQUEST_CUSTOM_REQUEST_ID] = self::createUuid();
        $headers[Request::HEADER_REQUEST_CUSTOM_GEOLOCATION] = self::getGeolocationHeader();
        
        // New Request
        $request = new Request(
            self::BUNQ_API_SERVICE_URL, 
            self::BUNQ_API_VERSION);
            
        $request->setMethod($method);
        $request->setEndpoint($endpoint);
        
        if (is_array($arrayBody)) {
            $request->setBodyFromArray($arrayBody);
        }
        
        foreach ($headers as $key => $value) {
            $request->setHeader($key, $value);
        }
        
        /* if ($endpoint == 'user/2858/monetary-account') {
            exit($signature);
        } */
        
        $request->setEndpoint($endpoint);
        
        if ($signature) {
            $request->setHeader(
                Request::HEADER_REQUEST_CUSTOM_SIGNATURE, 
                $request->getSignature($signature));
        }
        
        return $request->execute();
    }

    /**
    * Used for debugging.
    *
    * @param $string
    */
    public static function echoLine($string)
    {
        echo
            PHP_EOL .
            chr(27) . '[33m' .  $string . PHP_EOL .
            chr(27) . '[0m' . PHP_EOL;
    }

    /**
    * Create a new unique identifier.
    *
    * @return string The unique identifier.
    */
    public static function createUuid()
    {
        $randomInput = openssl_random_pseudo_bytes(self::UUID_BINARY_LENGTH);
        $randomInput[6] = chr(ord($randomInput[6]) & 0x0f | 0x40);
        $randomInput[8] = chr(ord($randomInput[8]) & 0x3f | 0x80);

        return vsprintf(
            self::UUID_FORMAT, 
            str_split(bin2hex($randomInput), 
            self::UUID_CHUNK_SIZE));
    }
    
    /**
    * Get the geolocation from the BUNQ_GEOLOCATION_ constants as an array.
    *
    * @return array The geolocation.
    */
    public static function getGeolocationArray()
    {
        $geolocation = [];
        $geolocation['latitude'] = self::BUNQ_GEOLOCATION_LATITUDE;
        $geolocation['longitude'] = self::BUNQ_GEOLOCATION_LONGITUDE;
        $geolocation['altitude'] = self::BUNQ_GEOLOCATION_ALTITUDE;
        $geolocation['radius'] = self::BUNQ_GEOLOCATION_RADIUS;

        return $geolocation;
    }

    /**
    * Get the geolocation from the BUNQ_GEOLOCATION_ constants as an string.
    *
    * @return string The geolocation.
    */
    public static function getGeolocationHeader()
    {
        return implode(' ', self::getGeolocationArray()) . ' ' . self::BUNQ_GEOLOCATION_COUNTRY;
    }
}