<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    private function client(): PendingRequest
    {
        return Http::baseUrl('https://api.paystack.co')
            ->acceptJson()
            ->asJson()
            ->withToken((string) config('services.paystack.secret_key'));
    }

    public function initialize(string $email, int $amountKobo, string $reference, ?string $callbackUrl = null, array $metadata = []): array
    {
        $payload = [
            'email' => $email,
            'amount' => $amountKobo,
            'currency' => 'NGN',
            'reference' => $reference,
        ];

        if (is_string($callbackUrl) && $callbackUrl !== '') {
            $payload['callback_url'] = $callbackUrl;
        }

        if ($metadata !== []) {
            $payload['metadata'] = $metadata;
        }

        /** @var Response $res */
        $res = $this->client()->post('/transaction/initialize', $payload);

        if (!$res->ok()) {
            return [
                'ok' => false,
                'message' => (string) ($res->json('message') ?? 'Unable to initialize payment'),
                'payload' => $res->json(),
                'status' => $res->status(),
            ];
        }

        return [
            'ok' => true,
            'data' => $res->json('data') ?? [],
        ];
    }

    public function verify(string $reference): array
    {
        /** @var Response $res */
        $res = $this->client()->get('/transaction/verify/'.urlencode($reference));

        if (!$res->ok()) {
            return [
                'ok' => false,
                'message' => (string) ($res->json('message') ?? 'Unable to verify payment'),
                'payload' => $res->json(),
                'status' => $res->status(),
            ];
        }

        return [
            'ok' => true,
            'data' => $res->json('data') ?? [],
        ];
    }

    public function publicKey(): string
    {
        return (string) config('services.paystack.public_key');
    }

    public function verifyWebhookSignature(string $rawBody, ?string $signature): bool
    {
        $secret = (string) config('services.paystack.webhook_secret');
        if ($secret === '') {
            $secret = (string) config('services.paystack.secret_key');
        }

        if ($secret === '' || !is_string($signature) || $signature === '') {
            return false;
        }

        $computed = hash_hmac('sha512', $rawBody, $secret);

        return hash_equals($computed, $signature);
    }
}
