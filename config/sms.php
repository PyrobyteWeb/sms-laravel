<?php

return [
    'provider' => env('SMS_PROVIDER', 'smsru'),
    'smsru' => [
        'protocol' => 'https',
        'domain' => 'sms.ru',
        'retry_counts' => 5,
        'key' => env('SMS_RU_KEY')
    ],
];
