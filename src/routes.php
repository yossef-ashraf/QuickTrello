<?php

use Illuminate\Support\Facades\Route;
use QuickTrello\Http\Controllers\TrelloWebhookController;
use QuickTrello\Http\Middleware\VerifyTrelloWebhook;

Route::prefix(config('quicktrello.webhook_prefix', 'api/trello/webhooks'))
    ->middleware(['api'])
    ->group(function () {
        Route::post('/', [TrelloWebhookController::class, 'handle']);
    });