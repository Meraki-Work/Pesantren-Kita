<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BrevoApiService
{
    private string $apiKey;
    private Client $client;

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://api.brevo.com/v3/',
            'timeout'  => 10,
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'api-key' => $this->apiKey,
            ],
        ]);
    }

    /**
     * Kirim OTP ke email
     */

    public function sendEmail(string $to, string $subject, string $html, string $text = null)
    {
        try {
            $payload = [
                'sender' => [
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    ['email' => $to]
                ],
                'subject' => $subject,
                'htmlContent' => $html,
                'textContent' => $text
            ];

            $response = $this->client->post('smtp/email', [
                'json' => $payload
            ]);

            return [
                'status' => 'success',
                'data' => json_decode($response->getBody(), true)
            ];
        } catch (\Exception $e) {
            Log::error("BrevoAPI Email Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function sendOtp(string $to, int $otp): array
    {
        try {
            $payload = [
                'sender' => [
                    'name' => 'PesantrenKita',
                    'email' => 'sandybom21@gmail.com', // domain harus terverifikasi
                ],
                'to' => [
                    ['email' => $to]
                ],
                'subject' => 'Kode OTP Anda',
                'htmlContent' => "<h1>Kode OTP Anda</h1><p>Kode OTP: <strong>{$otp}</strong></p>"
            ];

            $response = $this->client->post('smtp/email', [
                'json' => $payload
            ]);

            $body = json_decode((string)$response->getBody(), true);

            return ['status' => 'success', 'data' => $body];
        } catch (\Exception $e) {
            Log::error("Brevo API failed: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
