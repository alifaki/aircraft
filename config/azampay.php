<?php

return [
    'client_id'  => env('AZAMPAY_CLIENT_ID'),
    'client_secret' => env('AZAMPAY_CLIENT_SECRET'),
    'app_name'   => env('AZAMPAY_APP_NAME'),
    'api_key'    => env('AZAMPAY_API_KEY'),
    'environment'=> env('AZAMPAY_ENVIRONMENT', 'sandbox'), // sandbox or production
];
