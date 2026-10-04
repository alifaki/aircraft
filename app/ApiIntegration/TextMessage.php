<?php
/**
 * TextMessage Class for sending SMS messages via Beem API.
 */

namespace App\ApiIntegration;

use Illuminate\Support\Facades\Http;

class TextMessage
{
    private string $beemApiKey;
    private string $beemSecretKey;
    private string $beemUrl;
    private string $appId;

    public function __construct()
    {
        $this->beemApiKey = config('services.sms.beem_api_key');
        $this->beemSecretKey = config('services.sms.beem_secret_key');
        $this->beemUrl = config('services.sms.beem_url');
        $this->appId = config('app.id', 'SAMIS');
    }

    /**
     * Send SMS via Beem API.
     *
     * @param string $phone Single or multiple numbers separated by ';'
     * @param string $message
     * @return array
     */
    public function sendBeam(string $phone, string $message): array
    {
        if (empty($phone) || empty($message)) {
            return [
                'status' => 'failed',
                'message' => 'Phone number(s) or message cannot be empty'
            ];
        }

        $recipients = $this->formatPhoneNumbers($phone);
        if (empty($recipients)) {
            return [
                'status' => 'failed',
                'message' => 'No valid phone numbers provided'
            ];
        }

        $payload = [
            'source_addr' => 'SRMS',
            'schedule_time' => '',
            'encoding' => '0',
            'message' => $message,
            'recipients' => $recipients
        ];

        try {
            $response = Http::withBasicAuth($this->beemApiKey, $this->beemSecretKey)
                ->post($this->beemUrl, $payload);

            if ($response->failed()) {
                return [
                    'status' => 'failed',
                    'message' => 'Failed to send text message, unknown error'
                ];
            }

            $data = $response->json();

            return [
                'status' => 'success',
                'message' => $data['message'] ?? 'SMS sent successfully'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format multiple phone numbers into Beem API recipients.
     */
    private function formatPhoneNumbers(string $phone): array
    {
        $phones = array_filter(explode(';', $phone));
        $recipients = [];

        foreach ($phones as $index => $p) {
            $formattedPhone = $this->normalizePhoneNumber(trim($p));

            if ($formattedPhone) {
                $recipients[] = [
                    'recipient_id' => $index + 1,
                    'dest_addr' => $formattedPhone
                ];
            }
            // else: you could log invalid numbers here if needed
        }

        return $recipients;
    }

    /**
     * Normalize a single phone number to Tanzania E.164 format.
     */
    private function normalizePhoneNumber(string $phone): ?string
    {
        $phone = trim($phone);

        if (str_starts_with($phone, '0')) {
            $phone = '255' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        return (strlen($phone) === 12 && str_starts_with($phone, '255')) ? $phone : null;
    }
}
