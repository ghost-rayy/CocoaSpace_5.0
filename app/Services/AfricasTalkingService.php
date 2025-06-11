<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class AfricasTalkingService
{
    protected $smsService;

    public function __construct()
    {
        $username = env('AFRICASTALKING_USERNAME');  // correct usage
        $apiKey = env('AFRICASTALKING_API_KEY');
        $AT = new AfricasTalking($username, $apiKey);
        $this->sms = $AT->sms();
    }

    public function sendSMS($phone, $message)
    {
        return $this->sms->send([
            'to' => $phone,
            'message' => $message,
        ]);
    }
}
