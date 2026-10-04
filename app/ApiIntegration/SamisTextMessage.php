<?php
/**
 * SamisTextMessage Class for sending SMS messages via the SAMIS API.
 */

namespace App\ApiIntegration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SamisTextMessage
{
    private string $apiUrl;
    private string $appId;
    private string $smsCenter;
    private string $token;
    private string $brand;

    public function __construct()
    {
        $this->appId = config('app.samis.id', 'SAMIS');
        $this->brand = config('app.samis.brand', 'SAMIS');
        $this->apiUrl = config('app.samis.apiUrl');
        $this->smsCenter = config('services.sms.smsCenter', 'Huawei Router');
        $this->token = config('app.samis.token');
    }

    /**
     * Send an SMS message using the SAMIS API format.
     *
     * @param string $phone Single or multiple numbers separated by ';'
     * @param string $message The SMS message content
     * @return array
     */
    public function sendTextMessage(string $phone, string $message): array
    {
        if (empty($phone) || empty($message)) {
            return [
                'status'  => 'failed',
                'message' => 'Phone number and message content are required'
            ];
        }

        // Normalize and validate phone numbers
        $normalizedPhones = $this->normalizePhoneNumbers($phone);
        if (empty($normalizedPhones)) {
            return [
                'status'  => 'failed',
                'message' => 'No valid phone numbers provided'
            ];
        }
        
        // Join multiple numbers into one string separated by ';'
        $joinedPhones = implode(';', $normalizedPhones);

        $payload = [
            "direction"        => "outgoing",
            "phone_number"     => $joinedPhones,
            "message_content"  => "FROM {$this->brand}: ".$message,
            "sms_center"       => $this->smsCenter,
            "status"           => "pending",
            "message_id"       => $this->generateMessageId(),
            "received_at"      => Carbon::now('UTC')->toIso8601String(),
            "sent_at"          => Carbon::now('UTC')->addSeconds(5)->toIso8601String(),
        ];
        
        try {
            $response = Http::withToken($this->token)->post($this->apiUrl."router-sms/send", $payload);
            $responseData = $response->json();
           
            if ($response->failed() || !isset($responseData['success']) || $responseData['success'] === false) {
                // Read API error message if available
                $errorMessage = $responseData['message'] ?? 'Failed to send text message, unknown error';
                return [
                    'status'  => 'failed',
                    'message' => $errorMessage,
                    'errors'  => $responseData['errors'] ?? null,
                ];
            }

            // Success
            return [
                'status'  => 'success',
                'message' => $responseData['message'] ?? 'SMS queued successfully',
                'data'    => $responseData['data'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 'failed',
                'message' => 'Failed to send text message',
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize one or multiple phone numbers.
     *
     * @param string $phones e.g. "0774579698;255778484848;+255714000111"
     * @return array Valid normalized phone numbers
     */
    private function normalizePhoneNumbers(string $phones): array
    {
        $numbers = array_filter(explode(';', $phones));
        $validPhones = [];

        foreach ($numbers as $num) {
            $phone = trim($num);

            // Normalize single phone number
            if (str_starts_with($phone, '0')) {
                $phone = '255' . substr($phone, 1);
            } elseif (str_starts_with($phone, '+')) {
                $phone = substr($phone, 1);
            }

            // Validate phone format (Tanzania 12 digits, starting with 255)
            if (strlen($phone) === 12 && str_starts_with($phone, '255')) {
                $validPhones[] = $phone;
            }
        }

        return $validPhones;
    }

    /**
     * Generate unique message ID like SAMIS-XXXXX
     */
    private function generateMessageId(): string
    {
        return "{$this->appId}-" . strtoupper(Str::random(5));
    }
}
