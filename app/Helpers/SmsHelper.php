<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsHelper
{

    public static function send(string $phone, string $message): array
    {
        try {
            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification (unsafe)
            ])->asForm()->post(config('sms.url'), [
                'api_key'   => config('sms.api_key'),
                'senderid'  => config('sms.sender_id'),
                'contacts'  => $phone,
                'msg'       => $message,
                'type'      => 'text',
            ]);

            $responseData = [];
            try {
                $responseData = $response->json();
            } catch (\Throwable $e) {
                $responseData = ['raw' => $response->body()];
            }



            $success = $response->successful();

            Log::info('SMS Sent', [
                'phone' => $phone,
                'message' => $message,
                'response' => $responseData,
                'success' => $success,
            ]);



            return [
                'success' => $success,
                'response' => $responseData,
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