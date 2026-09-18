<?php

namespace app\components;

use Yii;
use yii\base\Component;

class SmsService extends Component
{
    public $apiKey;
    public $secretKey;
    public $senderId = 'INFO';
    public $endpoint = 'https://apisms.beem.africa/v1/send';

    /**
     * Send an SMS via Beem Africa.
     * Phone must be in international format without '+', e.g. 255712345678
     */
    public function send($phone, $message)
    {
        if (empty($this->apiKey) || empty($this->secretKey)) {
            Yii::warning('SMS not sent: Beem API credentials not configured.', 'sms');
            return false;
        }

        $phone = $this->normalizePhone($phone);

        $payload = [
            'source_addr' => $this->senderId,
            'encoding' => 0,
            'message' => $message,
            'recipients' => [
                ['recipient_id' => 1, 'dest_addr' => $phone],
            ],
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
            ],
            CURLOPT_TIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Yii::warning("SMS send failed (curl): $error", 'sms');
            return false;
        }

        $data = json_decode($response, true);
        $ok = isset($data['successful']) && $data['successful'] === true;

        if (!$ok) {
            Yii::warning('SMS send failed: ' . $response, 'sms');
        }

        return $ok;
    }

    /**
     * Normalize a Tanzanian number to international format (255XXXXXXXXX), no '+'.
     */
    public function normalizePhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits
        if (substr($phone, 0, 1) === '0') {
            $phone = '255' . substr($phone, 1);
        }
        return $phone;
    }
}
