<?php
namespace App\Services\BunqService;

use App\Services\BunqService\BunqServiceBase;

class BunqService extends BunqServiceBase 
{
    function __construct($api_key = FALSE) 
    {
        parent::__construct($api_key ? $api_key : env('BUNQ_KEY'));
    }
    
    public function getMonetaryAccounts()
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];
        
        $arrayBody = null;
        
        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account', 
            BunqRequest::METHOD_GET, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    public function makePayment($monetaryAccount, $amount, $counterparty_alias, $description = "")
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];
        
        $arrayBody = compact('amount', 'counterparty_alias', 'description');
        
        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account/' . $monetaryAccount . '/payment', 
            BunqRequest::METHOD_POST, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    public function paymentDetails($monetaryAccount, $paymentId)
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];
        
        $arrayBody = null;

        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account/' . $monetaryAccount . '/payment/' . $paymentId, 
            BunqRequest::METHOD_GET, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
}