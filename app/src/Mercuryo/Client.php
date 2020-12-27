<?php

namespace App\Mercuryo;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const PRODUCTION_BASE_URL = 'https://api.acquiring.mercuryo.io/v1.0';

    private const SANDBOX_BASE_URL = 'https://api.acquiring-sandbox.mercuryo.io/v1.0';

    private static $baseUrl;

    private $httpClient;

    public function __construct(HttpClientInterface $httpClientInterface)
    {
        static::$baseUrl = static::PRODUCTION_BASE_URL;

        $this->httpClient = $httpClientInterface;
    }

    /**
     * Use the sandbox base url for testing purposes.
     */
    public function enableSandboxMode()
    {
        static::$baseUrl = static::SANDBOX_BASE_URL;
    }

    /**
     * Create an invoice
     * 
     * @see https://api.acquiring.mercuryo.io/#operation/createInvoice
     */
    public function createAnInvoice(float $amount, string $returnUrl)
    {
        $endpoint = static::$baseUrl . '/invoices';

        $payload = [
            'currentcy' => 'EUR',
            'amount' => $amount,
            'name' => '',
            'description' => '',
            'merchant_order_id' => '',
            'return_url' => $returnUrl,
            'resolve_underpaid' => false,
            'resolve_overpaid' => false,
            'client' => [
                'email' => ''
            ]
        ];

        try {
            $response = $this->httpClient->request('POST', $endpoint, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $payload
            ]);

            if ($response->getStatusCode() == 201) {
                return $response->toArray();
            } else {
                throw new HttpResponseException($response->getContent());
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }


}