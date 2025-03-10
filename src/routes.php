<?php

use Illuminate\Support\Facades\Route;
use QuickTrello\Http\Controllers\QuickTrelloController;
use QuickTrello\Http\Controllers\TrelloWebhookController;
use QuickTrello\Http\Middleware\VerifyTrelloWebhook;

// QuickTrello Webhook Route
Route::prefix(config('quicktrello.webhook_prefix', 'api/trello/webhooks'))
    ->middleware(['api', VerifyTrelloWebhook::class])
    ->group(function () {
        Route::post('/', [TrelloWebhookController::class, 'handle']);
    });

// QuickTrello API Routes
Route::prefix('api/trello')
    ->middleware(['api'])
    ->group(function () {
        // Board routes
        Route::get('/boards', [QuickTrelloController::class, 'getBoards']);
        Route::get('/boards/{boardId}', [QuickTrelloController::class, 'getBoard']);
        Route::get('/boards/{boardId}/members', [QuickTrelloController::class, 'getBoardMembers']);
        
        // Organization routes
        Route::get('/organizations/{orgId}/members', [QuickTrelloController::class, 'getOrganizationMembers']);
        
        // List routes
        Route::get('/boards/{boardId}/lists', [QuickTrelloController::class, 'getLists']);
        Route::post('/lists', [QuickTrelloController::class, 'createList']);
        Route::put('/lists', [QuickTrelloController::class, 'updateList']);
        Route::post('/lists/archive', [QuickTrelloController::class, 'archiveList']);
        
        // Card routes
        Route::get('/lists/{listId}/cards', [QuickTrelloController::class, 'getCards']);
        Route::post('/cards', [QuickTrelloController::class, 'createCard']);
        Route::put('/cards', [QuickTrelloController::class, 'updateCard']);
        Route::post('/cards/move', [QuickTrelloController::class, 'moveCard']);
        Route::post('/cards/comment', [QuickTrelloController::class, 'addComment']);
        Route::post('/cards/due-date', [QuickTrelloController::class, 'addDueDate']);
        
        // Label routes
        Route::post('/cards/label', [QuickTrelloController::class, 'addLabel']);
        Route::delete('/cards/label', [QuickTrelloController::class, 'removeLabel']);
        
        // Member assignment routes
        Route::post('/cards/member', [QuickTrelloController::class, 'assignMemberToCard']);
        Route::delete('/cards/member', [QuickTrelloController::class, 'removeMemberFromCard']);
        Route::get('/cards/{cardId}/members', [QuickTrelloController::class, 'getCardMembers']);
        
        // Checklist routes
        Route::post('/cards/checklist', [QuickTrelloController::class, 'addChecklist']);
        Route::post('/checklists/item', [QuickTrelloController::class, 'addChecklistItem']);
        
        // Webhook management routes
        Route::get('/webhooks', [QuickTrelloController::class, 'getWebhooks']);
        Route::post('/webhooks', [QuickTrelloController::class, 'createWebhook']);
        Route::delete('/webhooks/{webhookId}', [QuickTrelloController::class, 'deleteWebhook']);
    });