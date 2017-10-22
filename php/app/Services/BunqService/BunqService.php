<?php
namespace App\Services\BunqService;

use Illuminate\Support\Facades\Cache;
use App\Services\BunqService\BunqServiceBase;

class BunqService extends BunqServiceBase 
{
    function __construct($api_key = FALSE) 
    {
        parent::__construct($api_key ? $api_key : env('BUNQ_KEY'));
    }
    
    public function getMonetaryAccounts()
    {
        $minutes = 5;

        $self = &$this;

        return Cache::remember('bunq_monetary_account', $minutes, function() use (&$self) {
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
        });
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
    
    public function createPaymentRequest($monetaryAccount, $amount_inquired, $counterparty_alias, $description = "")
    {
        $allow_bunqme = true;

        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];
        
        $arrayBody = compact('amount_inquired', 'counterparty_alias', 'description', 'allow_bunqme');
        
        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account/' . $monetaryAccount . '/request-inquiry', 
            BunqRequest::METHOD_POST, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    public function verifyPaymentRequest($monetaryAccount, $request_id)
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];
        
        $arrayBody = '{}';
        
        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account/' . $monetaryAccount . '/request-inquiry/' . $request_id, 
            BunqRequest::METHOD_GET, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
    
    public function revokePaymentRequest($monetaryAccount, $request_id)
    {
        $arrayHeaders = [
            Request::HEADER_REQUEST_CUSTOM_AUTHENTICATION => $this->sessionServerToken
        ];

        $status = 'REVOKED';
        
        $arrayBody = compact('status');
        
        return BunqRequest::makeRequest(
            'user/' . $this->userId . '/monetary-account/' . $monetaryAccount . '/request-inquiry/' . $request_id, 
            BunqRequest::METHOD_PUT, 
            $arrayBody,
            $arrayHeaders,
            $this->clientPrivateKey);
    }
}