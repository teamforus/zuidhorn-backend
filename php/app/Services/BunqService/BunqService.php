<?php
namespace App\Services\BunqService;

use bunq\Context\BunqContext;
use bunq\Model\Generated\Endpoint\MonetaryAccount;
use bunq\Model\Generated\Endpoint\Payment;
use bunq\Model\Generated\Endpoint\RequestInquiry;
use bunq\Model\Generated\Object\Amount;
use bunq\Model\Generated\Object\Pointer;
use bunq\Util\BunqEnumApiEnvironmentType;
use bunq\Context\ApiContext;

class BunqService
{
    private $bunqContextFilePath = "/bunq_context/context.json";
    private $deviceDescription = "My Device Description";

    /**
     * BunqService constructor.
     */
    function __construct()
    {
        $bunqContextFilePath = storage_path($this->bunqContextFilePath);

        $apiKey = config('bunq.key');
        $useSandbox = config('bunq.sandbox');

        BunqContext::loadApiContext(
            $this->requestApiContext(
                $bunqContextFilePath,
                $useSandbox,
                $apiKey,
                $this->deviceDescription
            )
        );
    }

    /**
     * @param $bunqContextFilePath
     * @param $useSandbox
     * @param $apiKey
     * @param $deviceDescription
     * @return ApiContext|static
     */
    private function requestApiContext(
        string $bunqContextFilePath,
        bool $useSandbox,
        string $apiKey,
        string $deviceDescription
    ) {
        if (!file_exists($bunqContextFilePath)) {
            if ($useSandbox) {
                $environmentType = BunqEnumApiEnvironmentType::SANDBOX();
            } else {
                $environmentType = BunqEnumApiEnvironmentType::PRODUCTION();
            }

            $permittedIps = [];

            $apiContext = ApiContext::create(
                $environmentType,
                $apiKey,
                $deviceDescription,
                $permittedIps
            );

            try {
                $apiContext->save($bunqContextFilePath);
            } catch (\Exception $exception) {}

            return $apiContext;
        } else {
            try {
                return ApiContext::restore($bunqContextFilePath);
            } catch (\Exception $exception) {
                unlink($bunqContextFilePath);
                return $this->requestApiContext(
                    $bunqContextFilePath,
                    $useSandbox,
                    $apiKey,
                    $deviceDescription
                );
            }
        }
    }

    /**
     * Get bank monetary account balance value
     * @return float
     */
    public function getBankAccountBalanceValue()
    {
        $monetaryAccount = MonetaryAccount::listing()->getValue()[0];

        return floatval(
            $monetaryAccount->getMonetaryAccountBank()->getBalance()->getValue()
        );
    }
    
    public function getMonetaryAccounts()
    {
        return MonetaryAccount::listing()->getValue();
    }

    /**
     * @param $amount
     * @param $iban
     * @param $name
     * @param string $description
     * @return int
     */
    public function makePayment(
        float $amount,
        string $iban,
        string $name,
        string $description = ""
    ) {
        return Payment::create(
            new Amount($amount, 'EUR'),
            new Pointer('IBAN', $iban, $name),
            $description
        )->getValue();
    }

    /**
     * @param $paymentId
     * @return Payment
     */
    public function paymentDetails(
        int $paymentId
    ) {
        return Payment::get($paymentId)->getValue();
    }

    /**
     * @param $amount
     * @param $email
     * @param $name
     * @param string $description
     * @return int
     */
    public function makePaymentRequest(
        float $amount,
        string $email,
        string $name,
        string $description = ""
    ) {
        return RequestInquiry::create(
            new Amount($amount, 'EUR'),
            new Pointer('EMAIL', $email, $name),
            $description,
            true
        )->getValue();
    }

    /**
     * @param $paymentRequestId
     * @return RequestInquiry
     */
    public function paymentRequestDetails(
        int $paymentRequestId
    ) {
        return RequestInquiry::get($paymentRequestId)->getValue();
    }

    /**
     * @param $paymentRequestId
     * @return \bunq\Model\Generated\Endpoint\BunqResponseRequestInquiry
     */
    public function revokePaymentRequest(
        int $paymentRequestId
    ) {
        return RequestInquiry::update($paymentRequestId, null, 'REVOKED');
    }
}