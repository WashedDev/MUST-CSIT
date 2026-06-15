<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneKhusaService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $organisationId;
    protected int $merchantAccountNumber;

    public function __construct()
    {
        $this->baseUrl = config('services.onekhusa.base_url');
        $this->apiKey = config('services.onekhusa.api_key');
        $this->apiSecret = config('services.onekhusa.api_secret');
        $this->organisationId = config('services.onekhusa.organisation_id');
        $this->merchantAccountNumber = (int) config('services.onekhusa.merchant_account_number');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && ! empty($this->apiSecret)
            && ! empty($this->organisationId) && ! empty($this->merchantAccountNumber);
    }

    public function initiateRequestToPay(
        string $referenceNumber,
        string $description,
        float $amount,
        string $capturedBy,
    ): ?array {
        $token = $this->getAccessToken();

        if (! $token) {
            return null;
        }

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/collections/requestToPay/initiate", [
                'merchantAccountNumber'  => $this->merchantAccountNumber,
                'transactionAmount'      => $amount,
                'transactionDescription' => $description,
                'referenceNumber'        => $referenceNumber,
                'capturedBy'             => $capturedBy,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        logger('OneKhusa requestToPay initiation failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return null;
    }

    public function getTransaction(string $transactionReferenceNumber): ?array
    {
        $token = $this->getAccessToken();

        if (! $token) {
            return null;
        }

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/collections/getTransaction", [
                'merchantAccountNumber'       => $this->merchantAccountNumber,
                'transactionReferenceNumber'  => $transactionReferenceNumber,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    protected function getAccessToken(): ?string
    {
        $response = Http::post("{$this->baseUrl}/account/getAccessToken", [
            'apiKey'                => $this->apiKey,
            'apiSecret'             => $this->apiSecret,
            'organisationId'        => $this->organisationId,
            'merchantAccountNumber' => $this->merchantAccountNumber,
        ]);

        if ($response->successful()) {
            return $response->json('accessToken');
        }

        logger('OneKhusa access token request failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return null;
    }
}
