<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class SmsHelper
{
    public static function send(string $phone, string $message, string $type = 'text'): array
    {
        try {
            $url = "https://msg.mram.com.bd/smsapi";

            // Clean and format phone number
            $contacts = preg_replace('/[^0-9]/', '', $phone);
            if (substr($contacts, 0, 2) !== '88') {
                $contacts = '88' . $contacts;
            }

            $data = [
                'api_key'  => trim(config('sms.api_key')),
                'senderid' => trim(config('sms.sender_id')),
                'type'     => $type,
                'contacts' => $contacts,
                'msg'      => $message,
            ];


            $fullUrl = $url . '?' . http_build_query($data);

            Log::info('SMS Request URL', ['url' => $fullUrl]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fullUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: Laravel/' . app()->version(),
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            Log::info('SMS Response', [
                'phone' => $phone,
                'message' => $message,
                'response' => $response,
                'error' => $error,
            ]);

            if ($error) {
                return ['success' => false, 'error' => $error];
            }

            if (strpos($response, '2001') !== false) {
                return ['success' => false, 'error' => 'MRAM API returned 2001: Invalid API Key or Sender ID'];
            }
            

            return ['success' => true, 'response' => $response];

        } catch (\Throwable $e) {
            Log::error('SMS Sending Exception', [
                'phone' => $phone,
                'message' => $message,
                'exception' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
