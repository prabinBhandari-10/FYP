<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class KhaltiService
{
    public function initiatePayment(array $payload): array
    {
        return $this->request('epayment/initiate/', $payload)->json();
    }

    public function lookupPayment(string $pidx): array
    {
        return $this->request('epayment/lookup/', ['pidx' => $pidx])->json();
    }

    protected function request(string $endpoint, array $payload): Response
    {
        $baseUrl = rtrim((string) config('services.khalti.base_url'), '/');
        $secretKey = (string) config('services.khalti.secret_key');

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'Authorization' => 'Key ' . $secretKey,
            ])
            ->post($endpoint, $payload)
            ->throw();
    }
}
