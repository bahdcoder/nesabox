<?php

return [
    'vendor_id' => env('PADDLE_VENDOR_ID'),

    'vendor_auth_code' => env('PADDLE_VENDOR_AUTH_CODE'),

    'public_key' => env('PADDLE_PUBLIC_KEY'),

    'webhook_uri' => 'paddle/webhook',

    'plans' => collect([
        'pro' => env('PADDLE_PRO_PLAN_ID', ''),
        'business' => env('PADDLE_BUSINESS_PLAN_ID', '')
    ])
];
