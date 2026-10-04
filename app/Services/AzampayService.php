<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AzampayService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $appName;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId     = config('azampay.client_id');
        $this->clientSecret = config('azampay.client_secret');
        $this->appName      = config('azampay.app_name');
        $this->apiKey       = config('azampay.api_key');
        $this->baseUrl      = config('azampay.environment') === 'sandbox'
            ? 'https://sandbox.azampay.co.tz'
            : 'https://checkout.azampay.co.tz';
    }

    /**
     * Get cached access token or request new one.
     */
    public function getToken(): string
    {
        $apiToken = config('azampay.environment') === 'sandbox'
        ? "https://authenticator-sandbox.azampay.co.tz"
        : "https://authenticator.azampay.co.tz";
        return Cache::remember('azampay_token', 50 * 60, function () {
            $response = Http::timeout(220)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiToken."/AppRegistration/GenerateToken", [
                'clientId'     => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'appName'      => $this->appName,
            ]);

            if (! $response->successful()) {
                throw new \Exception("Azampay auth failed: {$response->body()}");
            }

            return $response->json('data.accessToken');
        });
    }

    /**
     * Mobile money checkout (push to customer phone).
     */
    public function mobileCheckout(array $data): array
    {
        $token = $this->getToken();

        //Airtel", "Tigo" "Halopesa" "Azampesa" "Mpesa"
        $response = Http::timeout(220)->withHeaders([
            'Authorization' => "Bearer {$token}",
            'x-api-key'     => $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/azampay/mno/checkout", [
            'amount'       => (int) $data['amount'],
            'currency'     => $data['currency'] ?? 'TZS',
            'accountNumber'=> $data['phone'],
            'externalId'   => $data['external_id'],
            'provider'     => $data['provider'] ?? 'Mpesa',
        ]);

        if (! $response->successful()) {
            throw new \Exception("Azampay mobile checkout failed: {$response->body()}");
        }
        return $response->json();
    }

    /**
     * Get transaction status.
     */
    public function getTransactionStatus(string $transactionId): array
    {
        $token = $this->getToken();

        $response = Http::timeout(220)->withHeaders([
            'Authorization' => "Bearer {$token}",
            'x-api-key'     => $this->apiKey,
        ])->get("{$this->baseUrl}/transactions/{$transactionId}");

        if (! $response->successful()) {
            throw new \Exception("Azampay status failed: {$response->body()}");
        }

        return $response->json();
    }
}
