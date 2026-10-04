<?php

return [
    'whitelist_enabled' => env('IP_WHITELIST_ENABLED', false),
    'middleware' => [
        'except' => [
            'api/auth/*' // Exclude auth routes from IP restriction
        ]
    ]
];
