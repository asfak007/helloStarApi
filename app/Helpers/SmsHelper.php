<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    /**
     * Send SMS using any SMS API.
     */
    public static function send(string $phone, string $message): array
    {
        try {

            // ---- Example SMS Gateway API ----
            // Replace with your provider's URL + params
            $response = Http::asForm()->post(config('sms.url'), [
                'api_key'   => config('sms.api_key'),
                'senderid'  => config('sms.sender_id'),
                'number'    => $phone,
                'message'   => $message,
            ]);

            // Log for debugging
            Log::info('SMS Sent', [
                'phone' => $phone,
                'message' => $message,
                'response' => $response->body()
            ]);

            return [
                'success' => $response->successful(),
                'response' => $response->json() ?? $response->body(),
            ];

        } catch (\Throwable $e) {

            Log::error('SMS Sending Failed', [
                'error' => $e->getMessage(),
                'phone' => $phone,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}