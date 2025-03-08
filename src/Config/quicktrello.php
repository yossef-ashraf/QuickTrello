<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Trello API Key
    |--------------------------------------------------------------------------
    |
    | Your Trello API key from https://trello.com/app-key
    |
    */
    'api_key' => env('TRELLO_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Trello Token
    |--------------------------------------------------------------------------
    |
    | Your Trello token from https://trello.com/app-key
    |
    */
    'token' => env('TRELLO_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Trello API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for Trello API requests
    |
    */
    'base_url' => env('TRELLO_API_URL', 'https://api.trello.com/1'),

    /*
    |--------------------------------------------------------------------------
    | Register Routes
    |--------------------------------------------------------------------------
    |
    | Whether the package should register its routes automatically
    |
    */
    'register_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Webhook Route Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix for webhook routes
    |
    */
    'webhook_prefix' => 'api/trello/webhooks',

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Secret key for verifying webhook requests (optional)
    |
    */
    'webhook_secret' => env('TRELLO_WEBHOOK_SECRET'),
    
];